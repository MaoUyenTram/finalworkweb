$(function () {
    let aoo = [];
    let aoi = [];
    let hand = [];
    let gameid = $('#gameid').text();
    let clikclocid = "";
    let _token = $('meta[name="csrf-token"]').attr('content');
    let jsonresponse;
    let ndice = [];
    let cdice = [];

    //get imagelocations
    $.ajax({
        url: "/gamesession/getimages",
        type: "POST",
        data: {
            id: gameid,
            _token: _token,

        },
        success: function (response) {
            if (response.success) {
                console.log('test');
            }
        }
    }).done(function (data) {
        jsonresponse = data;
    });

    $('.ndice').each(function () {
        ndice.push(parseInt($(this).text()));
    });

    function getcdice() {
        let cdiceamount = $('.cdiceId').first().text();
        for (let z = 1; z <= parseInt(cdiceamount); z++) {
            let array = [];
            $('.cdice').each(function () {
                if (parseInt($(this).find('.cdiceId').text()) === z) {
                    array.push([$(this).find('.cdiceN').text(), parseInt($(this).find('.cdiceW').text())])
                }
            });
            cdice.push(array);
        }
    }

    getcdice();

    $('#rolldice').on('click', () => {
        let msg = $('#username').text() + ": rolled a ";
        for (let i = 0; i < ndice.length; i++) {
            let normaldob = randomIntFromInterval(1,ndice[i]);
            msg +=  normaldob + " ";
        }

        for (let z = 0; z < cdice.length;z++){
            let array = [];
            for(let e = 0;e < cdice[z].length;e++){
                for(let r =0;r < cdice[z][e][1];r++){
                    array.push(cdice[z][e][0]);
                }
            }
            msg += array[randomIntFromInterval(0,array.length)].toString() + " ";

        }


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

    });


    //pusher messages
    let pusher = new Pusher('8ec40fda71abdda99a12', {
        cluster: 'eu'
    });

    let channel = pusher.subscribe('gamesession' + gameid);

    channel.bind('setOwners', function (data) {
        aoo = data.request;
        $('#chat').append('<p>' + data.msg + '</p><br>');
    });
    channel.bind('change', function (data) {
        aoi = data.request;
        recreate();
        $('#chat').append('<p>' + data.msg + '</p><br>');

    });
    channel.bind('places', function (data) {
        $('#chat').append('<p>' + data.msg + '</p><br>');
        let img = document.createElement('img');
        img.src = "/uploads/" + data.request[0] + ".jpg";
        img.className = "absolute resize";
        img.style.cssText = "left:" + data.request[1] * $('#board').width() + "px;top:" + data.request[2] * $('#board').height() + "px;width:60px;height:60px";
        $('#board').append(img);

    });
    channel.bind('message', function (data) {
        $('#chat').append('<p>' + data.msg + '</p><br>');
    });

    function recreate() {
        for (let i = 0; i < aoi.length; i++) {
            if (aoi[i].length === 1) {
                aoi[i] = [aoi[i], []];

            }
        }
    }

    //array of items
    $('.allobj').each(function () {
        let name = $(this).find('.items').attr('name');
        let array = [];
        $(this).find('.items').each(function () {
            let iname = $(this).find('p.iname').text();
            let iamount = $(this).find('p.iamount').text().toString();
            array.push([iname, iamount])
        });
        aoi.push([name, array]);

    });

    $('#board').on('click', function (e) {
        let x = e.pageX - $(this).offset().left - 30;
        let y = e.pageY - $(this).offset().top - 30;
        let z = -1;
        for (let i = 0; i < jsonresponse.length; i++) {
            if (jsonresponse[i].name === hand[0][0]) {
                z = i;
                break;
            }
        }
        let img = document.createElement('img');
        img.src = "/uploads/" + jsonresponse[z].topsidelocation + ".jpg";
        img.className = "absolute";
        img.style.cssText = "left:" + x + "px;top:" + y + "px;width:60px;height:60px";
        $('#board').append(img);
        hand.shift();

        let msg = $('#username').text() + ": placed an item";
        let data = [jsonresponse[z].topsidelocation, x / $(this).width(), y / $(this).height()];
        $.ajax({
            url: "/gamesession/places",
            type: "POST",
            data: {
                data: data,
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
    $('#board').on('contextmenu', function (e) {
    });

    $('#sendmsg').on('click', function () {
        let msg = $('#username').text() + ": " + $('#msg').val();
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

    //pilemenu
    //visibility not implemented
    $('.button').on('contextmenu', function (e) {
        e.preventDefault();
        let pile = e.target.id;
        let pilename = e.target.name;
        let username = $('#username').text();
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
    });

    // add item to a pile
    $('.button').on('click', function (e) {
        //console.log(aoi);
        let name = e.target.name;
        let aoiindex = -1;
        for (let a = 0; a < aoi.length; a++) {
            if (aoi[a][0] === name) {
                aoiindex = a;
                break;
            }
        }
        for (let i = 0; i < 1; i++) {
            aoi[aoiindex][1].push([hand[i][0], hand[i][1]]);
            $('#' + name + 'box').append('<button class="items w-full ' + name +
                '" name="' + name +
                '"> <p class="iname">' + hand[i][0] +
                '</p> <p class="iamount">' + hand[i][1] +
                '</p> </button>');

        }
        hand.shift();
        change('a pile has changed');
    });

    //remove item from pile
    $('.allobj .itembox').on('contextmenu', '.items', function (e) {
        e.preventDefault();
        $('#menunotowner').attr('hidden', true);
        let iname = $(this).find('.iname').text();
        let iamount = $(this).find('p.iamount').text().toString();
        let item = [iname, iamount];
        let check = -1;
        for (let i = 0; i < aoi.length; i++) {
            if (aoi[i][0] === $(this).attr('name')) {
                //console.log('where1');
                for (let b = 0; b < aoi[i][1].length; b++) {
                    //console.log(JSON.stringify(aoi[i][1][b]), JSON.stringify(item));
                    if (JSON.stringify(aoi[i][1][b]) === JSON.stringify(item)) {
                        //console.log('where2');
                        for (let c = 0; c < hand.length; c++) {
                            if (hand[c][0] === aoi[i][1][b][0]) {
                                check = c;
                                break;
                            }
                        }
                        if (check === -1 || hand.length < 1) {
                            hand.push([aoi[i][1][b][0], 1]);
                        } else {
                            hand[check][1] += 1;
                        }
                        aoi[i][1][b][1] -= 1;
                        $(this).find('p.iamount').text(aoi[i][1][b][1]);
                        if (aoi[i][1][b][1] === 0) {
                            aoi[i][1].splice(b, 1);
                            $(this).remove();
                        }
                        break;

                    }
                }
            }
        }
        //console.log(aoi,hand);
        change('a pile has changed');
    });


    $('#menunotowner').on("click", function (e) {
        $('#menunotowner').attr('hidden', true);
    });

    $(document).on("click", function (e) {
        $('#menunotowner').attr('hidden', true);
    });

    //random number
    //antwoord van francisc (29 augusts 2011)
    //https://stackoverflow.com/questions/4959975/generate-random-number-between-two-numbers-in-javascript
    //geraadpleegd op 8/06/2021
    function randomIntFromInterval(min, max) { // min and max included
        return Math.floor(Math.random() * (max - min + 1) + min)
    }

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
    });

    //shuffle array cannot be implemented atm
    //antwoord van ashleedawg(9 maart 2020)
    //https://stackoverflow.com/questions/2450954/how-to-randomize-shuffle-a-javascript-array
    //geraadpleegd op 8/06/2021
    function shuffleArray(array) {
        for (let i = array.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [array[i], array[j]] = [array[j], array[i]];
        }
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


});
