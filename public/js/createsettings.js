$(function () {
    let amount = 0;
    let cdicearray = [];


    $('#addoption').on('click',function () {
        let option = $('#owner').val();
        $('.ownerselect').append(new Option(option,option));
    });

    $('#customclick').on('click', function () {
        amount = parseInt($('#dxcustom').val());
        if (isNaN(amount)){
            return 0;
        }
        $('#weightsform').empty();
        for (let i = 0; i < amount; i++) {
            $('#weightsform').append(
                `<div class="flex"><p>name: </p> <input type="text" id="name` + i +
                `" class=" appearance-none bg-transparent border-none text-gray-700 mr-3 py-1 px-2 leading-tight focus:outline-none"><p>weight: </p> <input type="number" id="weight`+
                i +`" class="  appearance-none bg-transparent border-none text-gray-700 mr-3 py-1 px-2 leading-tight focus:outline-none"> </div>`);
        }
        $('#weightsbutton').removeAttr("hidden");
        $('#weightscancel').removeAttr("hidden");
    });

    $('#weightsbutton').on('click', function () {

        let _token = $('meta[name="csrf-token"]').attr('content');
        let id = $('#gameIdtest').val();
        let diceId = (parseInt($('#cdicex').val()) +1);
        for (let i = 0; i < amount; i++) {
            let a = $('#name'+i).val();
            let b = $('#weight'+i).val();
            $.ajax({
                url: "/piles/setcdice",
                type:"POST",
                data:{
                    name:a,
                    weight:b,
                    diceId:diceId,
                    id:id,
                    _token: _token
                },
                success:function(response){
                    console.log('succes')
                },
            });

        }
        $('#weightsform').empty();
        $('#weightsbutton').attr("hidden",true);
        $('#weightscancel').attr("hidden",true);
        $('#cdicex').attr('value',diceId);
        $('#customx').html(diceId);
    });

    $('#save').on('click', function () {
        let _token = $('meta[name="csrf-token"]').attr('content');
        let id = $('#gameIdtest').val();
        let url = "";
        $('tr').each(function() {
            let pileid = $(this).find('p').text();
            let select = $(this).find('select.ownerselect').val();

            $.ajax({
                url: "/piles/addowner",
                type:"POST",
                async:false,
                data:{
                    pileid:pileid,
                    owner:select,
                    id:id,
                    _token: _token
                },
                success:function(response){
                    if (response.success)  {
                        url = response.url;
                    }
                },
            });


        });
        window.location.href = url;

    });

    $('#dalldice').on('click', function () {
        let _token = $('meta[name="csrf-token"]').attr('content');
        let id = $('#gameIdtest').val();
        $.ajax({
            url: "/piles/destroydice",
            type:"POST",
            data:{
                id:id,
                _token:_token
            },
            success:function(response){
                if (response.success)  {
                    console.log(response);
                }
            },
        });
        $('#normalx').html(0);
        $('#customx').html(0);
        $('#cdicex').attr('value',0);


    });


    $('#weightscancel').on('click', function () {
        $('#weightsform').empty();
        $('#weightsbutton').attr("hidden",true);
        $('#weightscancel').attr("hidden",true);
    });
});



