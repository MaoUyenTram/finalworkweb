$(function () {
    let aoo = [];
    let aoi = [];
    let hand = [];
    let gameid = $('#gameid').text();
    let clikclocid = "";
    let _token = $('meta[name="csrf-token"]').attr('content');
    let allitems = [];
    let ndice = [];
    let cdicejs = [];
    let username = "";
    let pileowner = [];
    let board = [];


    function getcdice(cdice) {
        for (let z = 1; z <= parseInt(cdice[0].diceId); z++) {
            let array = [];
            $.each(cdice, function (i, v) {
                if (parseInt(v.diceId) === z) {
                    array.push([v.name, parseInt(v.weight)])
                }
            });
            cdicejs.push(array);
        }
    }

    //get all items
    $.ajax({
        url: "/gamesession/getalldata",
        type: "POST",
        data: {
            id: gameid,
            _token: _token,

        },
        success: function (data) {
            allitems = data.pileItems;
            putitemsinpiles(allitems);
            username = data.name;
            ndice = data.ndice;
            getcdice(data.cdice);
        }
    }).done(function (data) {
        alert('all loaded')


        //console.log(data);
    });


    $('#rolldice').on('click', () => {
        //console.log(aoi, hand);
        let msg = username + ": rolled a ";
        for (let i = 0; i < ndice.length; i++) {
            let normaldob = randomIntFromInterval(1, ndice[i]);
            msg += normaldob + " ";
        }

        for (let z = 0; z < cdicejs.length; z++) {
            let array = [];
            for (let e = 0; e < cdicejs[z].length; e++) {
                for (let r = 0; r < cdicejs[z][e][1]; r++) {
                    array.push(cdicejs[z][e][0]);
                }
            }
            msg += array[randomIntFromInterval(0, array.length - 1)].toString() + " ";

        }


        $.ajax({
            url: "/gamesession/message",
            type: "POST",
            data: {
                data: null,
                id: gameid,
                msg: msg,
                _token: _token
            },
            success: function (response) {
                if (response.success) {
                    console.log(response);
                }
            },
        });

    });


    //pusher messages
    let pusher = new Pusher('8ec40fda71abdda99a12', {
        cluster: 'eu'
    });

    let channel = pusher.subscribe('gamesession' + gameid);

    channel.bind('setOwners', function (data) {
        aoo = data.request;
        pileowner = [];
        $.each(data.request, function (i, v) {
            if (v[1] === username) {
                pileowner.push(v[0]);
            }
        });
        $('#chat').append('<p>' + data.msg + '</p><br>');
    });
    channel.bind('change', function (data) {
        aoi = data.request;
        recreate();
        $('#chat').append('<p>' + data.msg + '</p><br>');

    });
    channel.bind('places', function (data) {
        if (data.request != null) {
            board = data.request;
        }else {
            board = [];
        }
        $('#chat').append('<p>' + data.msg + '</p><br>');
        $('#board').empty();
        $.each(board, function (k, v) {
            let img = document.createElement('img');
            img.src = "/uploads/" + v[1] + ".jpg";
            img.className = "absolute resize imgitem";
            img.id = k;
            img.style.cssText = "left:" + v[2] * $('#board').width() + "px;top:" + v[3] * $('#board').height() + "px;width:60px;height:60px";
            $('#board').append(img);
        });

    });
    channel.bind('message', function (data) {
        $('#chat').append('<p>' + data.msg + '</p><br>');
    });

    function recreate() {
        for (let i = 0; i < aoi.length; i++) {
            if (aoi[i].length === 4) {
                aoi[i] = [aoi[i], []];
            }
        }

    }

    function draw(name) {
        for (let i = 0; i < aoi.length; i++) {
            if (aoi[i][0].toString() === name.toString()) {
                if (aoi[i][4].length > 0) {
                    hand.push(aoi[i][4][0]);
                    aoi[i][4].shift();
                }
                //console.log(hand, aoi);
            }
        }
        change('drew a card');
    }

    function steal(name) {
        for (let i = 0; i < aoi.length; i++) {
            if (aoi[i][0].toString() === name.toString()) {
                if (aoi[i][4].length > 0) {
                    let random = randomIntFromInterval(0, aoi[i][4].length - 1);
                    if (aoi[i][1].toString() === "deck") {
                        hand.push(aoi[i][4][random]);
                        aoi[i][4].splice(random, 1);
                        break;
                    } else {
                        hand.push(aoi[i][4][random][0]);
                        aoi[i][4][random][1] -= 1;
                        if (aoi[i][4][random][1] === 0) {
                            aoi[i][4].splice(random, 1);
                        }
                        break;

                    }

                }
            }
            //console.log(hand, aoi);
        }
        change('stole a card');
    }

    let menuvis1 = {
        callback: function (key, opt) {
            //console.log(key, opt.$trigger[0].name);
            switch (key.toString()) {
                case "draw":
                    draw(opt.$trigger[0].name);
                    break;
                case "shuffle":
                    shuffleArray(opt.$trigger[0].name);
                    break;
                case "open":
                    openitems(opt.$trigger[0].name)
                    break;
                default:
            }
        },
        items: {
            "draw": {name: "draw"},
            "shuffle": {name: "shuffle"},
            "open": {name: "open"},
        }
    };
    let menuvis0 = {
        callback: function (key, opt) {
            //console.log(key, opt.$trigger[0].name);
            switch (key.toString()) {
                case "draw":
                    draw(opt.$trigger[0].name);
                    break;
                case "shuffle":
                    shuffleArray(opt.$trigger[0].name);
                    break;
                default:
            }
        },
        items: {
            "draw": {name: "draw"},
            "shuffle": {name: "shuffle"},
        }
    };

    let menuvis3 = {
        callback: function (key, opt) {
            //console.log(key, opt.$trigger[0].name);
            switch (key.toString()) {
                case "steal":
                    steal(opt.$trigger[0].name);
                    break;
                case "shuffle":
                    shuffleArray(opt.$trigger[0].name);
                    break;
                case "open":
                    openitems(opt.$trigger[0].name)
                    break;
                default:
            }
        },
        items: {
            "steal": {name: "steal"},
            "shuffle": {name: "shuffle"},
            "open": {name: "open"}

        }
    };
    let menuvis2 = {
        callback: function (key, opt) {
            //console.log(key, opt.$trigger[0].name);
            switch (key.toString()) {
                case "steal":
                    steal(opt.$trigger[0].name);
                    break;
                case "shuffle":
                    shuffleArray(opt.$trigger[0].name);
                    break;
                default:
            }
        },
        items: {
            "steal": {name: "steal"},
            "shuffle": {name: "shuffle"},

        }
    };


    $.contextMenu({
        selector: '.button',
        trigger: 'right',
        build: function ($triggerElement, e) {
            let thereturn = false;
            if ($.inArray(e.target.id.toString(), pileowner) !== -1) {
                for (let i = 0; i < aoi.length; i++) {
                    if (aoi[i][0].toString() === e.target.name.toString()) {
                        if (aoi[i][1].toString() === "deck") {
                            if (aoi[i][2].toString() === "1") {
                                thereturn = menuvis1;
                            } else {
                                thereturn = menuvis0;
                            }
                        } else {
                            openitems(e.target.name);
                        }
                    }

                }
            } else if (pileowner.length > 0) {
                for (let i = 0; i < aoi.length; i++) {
                    if (aoi[i][0].toString() === e.target.name.toString()) {
                        if (aoi[i][2].toString() === "3") {
                            thereturn = menuvis3;
                        } else {
                            thereturn = menuvis2;
                        }
                    }
                }
            }
            return thereturn;
        }

    });

//array of items
    function putitemsinpiles(allitems) {
        $('.allobj').each(function () {
            let name = $(this).find('.button').attr('name');
            let pileid = $(this).find('.pileid').text();
            let piletype = $(this).find('.piletype').text();
            let pilevis = $(this).find('.pilevis').text();
            let private = $(this).find('.private').text();
            let array = [];
            $.each(allitems, function (i, v) {
                if (v.PileId == pileid) {
                    if (piletype == "deck") {
                        for (let a = 0; a < v.amount; a++) {
                            array.push(v.name);
                        }
                    } else {
                        array.push([v.name, v.amount]);
                    }
                }
            });
            aoi.push([name, piletype, pilevis, private, array]);
        });
    }

    $('#board').on('click', function (e) {
        let x = e.pageX - $(this).offset().left - 30;
        let y = e.pageY - $(this).offset().top - 30;
        let z = -1;
        for (let i = 0; i < allitems.length; i++) {
            if (allitems[i].name === hand[0]) {
                z = i;
                break;
            }
        }
        if (z === -1){
            return 0;
        }
        /*
        let img = document.createElement('img');
        img.src = "/uploads/" + allitems[z].topsidelocation + ".jpg";
        img.className = "absolute imgitem resize";
        img.style.cssText = "left:" + x + "px;top:" + y + "px;width:60px;height:60px";
        $('#board').append(img);
         */
        hand.shift();
        let msg = username + ": placed an item";
        let data = [allitems[z].name,allitems[z].topsidelocation, x / $(this).width(), y / $(this).height()];
        board.push(data);
        $.ajax({
            url: "/gamesession/places",
            type: "POST",
            data: {
                data: board,
                id: gameid,
                msg: msg,
                _token: _token
            },
            success: function (response) {
                if (response.success) {
                    console.log(response);
                }
            },
        });
    });

//unimplemented remove object
    $('#board').on('contextmenu', '.imgitem', function (e) {
        e.preventDefault();
        //console.log(board[parseInt(e.target.id)][0])
        hand.push(board[parseInt(e.target.id)][0]);
        board.splice(parseInt(e.target.id),1);
        let msg = username + ": placed an item";
        $.ajax({
            url: "/gamesession/places",
            type: "POST",
            data: {
                data: board,
                id: gameid,
                msg: msg,
                _token: _token
            },
            success: function (response) {
                if (response.success) {
                    console.log(response);
                }
            },
        });

    });

    $('#sendmsg').on('click', function () {
        let msg = username + ": " + $('#msg').val();
        $.ajax({
            url: "/gamesession/setowners",
            type: "POST",
            data: {
                data: null,
                id: gameid,
                msg: msg,
                _token: _token
            },
            success: function (response) {
                if (response.success) {
                    console.log(response);
                }
            },
        });
        $('#msg').empty();
    });

// change owners
    $('#changO').on('click', function () {
        aoo = [];
        $('tr').each(function () {
            let owner = $(this).find('p').text();
            let select = $(this).find('select.owners').val();
            aoo.push([owner, select]);
        });
        let msg = 'owners have changed';
        let _token = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: "/gamesession/setowners",
            type: "POST",
            data: {
                data: aoo,
                id: gameid,
                msg: msg,
                _token: _token
            },
            success: function (response) {
                if (response.success) {
                    console.log(response);
                }
            },
        });
    });

    function openitems(name) {
        $(".itembox").empty();
        for (let i = 0; i < aoi.length; i++) {
            if (aoi[i][0].toString() === name.toString()) {
                if (aoi[i][1].toString() === "deck") {
                    $.each(aoi[i][4], function (i, v) {
                        $('#' + name + 'box').append('<button class="items w-full ' + name +
                            '" name="' + name +
                            '"> <p class="iname">' + v +
                            '</p> </button>');
                    });
                } else {
                    $.each(aoi[i][4], function (i, v) {
                        $('#' + name + 'box').append('<button class="items w-full ' + name +
                            '" name="' + name +
                            '"> <p class="iname">' + v[0] +
                            '</p> <p class="iamount">' + v[1] +
                            '</p> </button>');
                    });
                }
            }
        }
    }


//pilemenu
//visibility not implemented
    /*$('.button').on('contextmenu', function (e) {
        e.preventDefault();
        let pile = e.target.id;
        let pilename = e.target.name;
        let check = -1;
        for (let i = 0; i < aoo.length; i++) {
            if (aoo[i][1] === username) {
                //if owner of pile
                if (aoo[i][0] === pile) {
                    check = i;
                    break;
                }
            }
        }
        if (check > -1) {
            $('.' + pilename).removeAttr('hidden');
            $('#menunotowner').attr('hidden', true);
        } else {
            $('#menunotowner').removeAttr('hidden');
            clikclocid = pilename;
            $("#menunotowner").css({left: e.pageX, top: e.pageY});
        }
    });*/

// add item to a pile
    $('.button').on('click', function (e) {
        //console.log(aoi);
        if (hand.length === 0) {
            console.log(hand, aoi);
            return 0;
        }
        let name = e.target.name;
        let aoiindex = -1;
        for (let a = 0; a < aoi.length; a++) {
            if (aoi[a][0] === name) {
                aoiindex = a;
                break;
            }
        }
        if (aoi[aoiindex][1].toString() === "deck") {
            aoi[aoiindex][4].push(hand[0]);
            $('#' + name + 'box').append('<button class="items w-full ' + name +
                '" name="' + name +
                '"> <p class="iname">' + hand[0] +
                '</p> </button>');

        } else {
            let stack = -1;
            for (let i = 0; i < aoi[aoiindex][4]; i++) {
                if (aoi[aoiindex][4][i][0].toString() === hand[0].toString()) {
                    aoi[aoiindex][4][i][1] += 1;
                    break;
                } else {
                    aoi[aoiindex][4].push([hand[0], 1]);
                    $('#' + name + 'box').append('<button class="items w-full ' + name +
                        '" name="' + name +
                        '"> <p class="iname">' + hand[0] +
                        '</p> <p class="iamount">1</p> </button>');
                }
            }


        }
        hand.shift();
        change('a pile has changed');
        console.log(hand, aoi);
    });

//remove item from pile
    $('.allobj .itembox').on('contextmenu', '.items', function (e) {
        e.preventDefault();
        let iname = $(this).find('.iname').text();
        //let iamount = $(this).find('p.iamount').text().toString();
        let item = iname;
        for (let i = 0; i < aoi.length; i++) {
            if (aoi[i][0] === $(this).attr('name')) {
                for (let b = 0; b < aoi[i][4].length; b++) {
                    //console.log(JSON.stringify(aoi[i][1][b]), JSON.stringify(item));
                    if (aoi[i][1].toString() === "deck") {
                        console.log(aoi[i][4][b].toString(), iname.toString());
                        if (aoi[i][4][b].toString() === iname.toString()) {
                            hand.push(aoi[i][4][b]);
                            aoi[i][4].splice(b, 1);
                            $(this).remove();
                            break;
                        }
                    } else {
                        if (aoi[i][4][b][0].toString() === iname.toString()) {
                            hand.push(aoi[i][4][b][0]);
                            aoi[i][4][b][1] -= 1;
                            if (aoi[i][4][b][1] === 0) {
                                aoi[i][4].splice(b, 1);
                                $(this).remove();
                            }
                            break;
                        }
                    }
                }
            }
        }

        console.log(aoi, hand);
        change('a pile has changed');

    });

    /*
        $('#menunotowner').on("click", function (e) {
            $('#menunotowner').attr('hidden', true);
        });

        $(document).on("click", function (e) {
            $('#menunotowner').attr('hidden', true);
        });
    */
//random number
//antwoord van francisc (29 augusts 2011)
//https://stackoverflow.com/questions/4959975/generate-random-number-between-two-numbers-in-javascript
//geraadpleegd op 8/06/2021
    function randomIntFromInterval(min, max) { // min and max included
        min = Math.ceil(min);
        max = Math.floor(max);
        return Math.floor(Math.random() * (max - min + 1) + min)
    }

    /*
    //draw random card visual not updating piles
        $('#draw').on('click', function () {
            let pindex = -1;
            let check = -1;

            for (let i = 0; i < aoi.length; i++) {
                if (aoi[i][0] === clikclocid) {
                    pindex = i;
                }
            }
            let random = randomIntFromInterval(0, aoi[pindex][1].length - 1);
            for (let c = 0; c < hand.length; c++) {
                if (hand[c][0] === aoi[pindex][1][random][0]) {
                    check = c;
                    break;
                }
            }
            if (check === -1 || hand.length < 1) {
                hand.push([aoi[pindex][1][random][0], 1]);
            } else {
                hand[check][1] += 1;
            }
            aoi[pindex][1][random][1] -= 1;
            //$(this).find('p.iamount').text(aoi[pindex][1][random][1]);
            if (aoi[pindex][1][random][1] === 0) {
                aoi[pindex][1].splice(random, 1);
                // $(this).remove();
            }
            change("drew an item");
        });*/

//shuffle array cannot be implemented atm
//antwoord van ashleedawg(9 maart 2020)
//https://stackoverflow.com/questions/2450954/how-to-randomize-shuffle-a-javascript-array
//geraadpleegd op 8/06/2021
    function shuffleArray(array) {
        $.each(aoi, function (k, v) {
            if (v[0].toString() === array.toString()) {
                for (let i = v[4].length - 1; i > 0; i--) {
                    const j = Math.floor(Math.random() * (i + 1));
                    [v[4][i], v[4][j]] = [v[4][j], v[4][i]];
                }
            }
        });

    }

// a change in aoi occurred
    function change(msg) {
        let _token = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: "/gamesession/changes",
            type: "POST",
            data: {
                data: aoi,
                id: gameid,
                msg: msg,
                _token: _token
            },
            success: function (response) {
                if (response.success) {
                    console.log(response);
                }
            },
        });
    }


})
;
