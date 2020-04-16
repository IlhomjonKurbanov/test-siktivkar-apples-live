<?php

/* @var $this yii\web\View */

$this->title = 'My Apples Test App ))';
?>
<div class="site-index">

    <div class="jumbotron">

        <h1>Яблоки</h1>

        <button class="btn btn-lg btn-success" onclick="generateApplesList()">Генерация</button>

    </div>

    <div class="body-content">

        <div class="row" id="applesList">


                <!-- <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 card">
                    <div class="apple-card">
                        <div class="apple" id="">
                        
                        </div>

                        <h3>
                            <button class="btn btn-sm btn-info" type="button" onclick="fallenApple('a1')">Упасть</button>
                            <button class="btn btn-sm btn-primary" type="button" onclick="eatenApple('a2')">Cъесть на</button>
                            <select class="input-sm" id="inputSelect">
                                <option selected value="25">25%</option>
                                <option value="50">50%</option>
                                <option value="75">75%</option>
                                <option value="100">100%</option>
                            </select>

                        </h3>
                        
                        

                    </div>
                </div> -->


        </div>

    </div>

</div>


<?php

$this->registerJs(
    '

    function generateApplesList(){
        $.ajax({
            url: "admin/site/generate-apples",
            type: "post",
            data: {
                _csrf: yii.getCsrfToken(),
            },
            cache: false,
            success: function (responsData) {

                $("#applesList").html("");

                // alert(JSON.stringify(responsData[0].color));
                // alert(responsData[0].color);


                for (var i = 0; i < 12; i++) {

                    var appleState = ""
                    if (responsData[i].state == 1) {
                        appleState = "Висит на дереве"
                    } else if (responsData[i].state == 2) {
                        appleState = "Лежит на земле"
                    } else if (responsData[i].state == 3) {
                        appleState = "Гнилое яблоко"
                    }
                     
                    $("#applesList").append(\'<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 card"><div class="apple-card"><div class="apple" id="\'+responsData[i].id+\'" style="background: \'+responsData[i].color+\';"></div><h3><button class="btn btn-sm btn-info" type="button" style="margin-right:2px;" onclick="fallenApple(\'+responsData[i].id+\')">Упасть</button><button class="btn btn-sm btn-primary" type="button" style="margin-right:2px;" onclick="eatenApple(\'+responsData[i].id+\')">Cъесть на</button><select class="input-sm" id="inputSelect\'+responsData[i].id+\'" style="padding:0px !important;"><option selected value="25">25%</option><option value="50">50%</option><option value="75">75%</option><option value="100">100%</option></select><div style="position: absolute; display: block; width: 180px; text-align: center; color: white; top:-150px;"><span id="sizeId\'+responsData[i].id+\'">\'+responsData[i].size+\'%</span><br><span  id="stateId\'+responsData[i].id+\'">\'+appleState+\'</span></div></h3></div></div>\');

                } 

            },

        });

    }


    function fallenApple(appleId) {
        
        $.ajax({
            url: "admin/site/fallen-apple",
            type: "post",
            data: {
                id: appleId,
                _csrf: yii.getCsrfToken(),
            },
            cache: false,
            success: function (responsData) {

                if ( responsData.stateText == "" ) {
                    alert("Error");
                } else {

                    var appleState = ""

                    if (responsData.currentApple.state == 1) {
                        appleState = "Висит на дереве"
                    } else if (responsData.currentApple.state == 2) {
                        appleState = "Лежит на земле"
                    }

                    $("#stateId"+appleId).text(appleState);
                    alert(responsData.stateText);

                }

            },

        });
        
    }


    function eatenApple(appleId) {

        var eatenPart = $("#inputSelect"+appleId).val();

        $.ajax({
            url: "admin/site/eaten-apple",
            type: "post",
            data: {
                id: appleId,
                eatenPart: eatenPart,
                _csrf: yii.getCsrfToken(),
            },
            cache: false,
            success: function (responsData) {

                if ( responsData.sizeText == "" ) {
                    alert("Error");
                } else {

                    if ( responsData.currentApple.size == 0 ) {
                        $("#sizeId"+appleId).text(responsData.currentApple.size+"%");
                        $("#stateId"+appleId).text(responsData.sizeText);
                    } else {
                        $("#sizeId"+appleId).text(responsData.currentApple.size+"%");
                    }
                   
                    alert(responsData.sizeText);

                }

            },

        });


    }
    


    
    ',

    yii\web\View::POS_HEAD
);


$this->registerJs(
    '

        $.ajax({
            url: "admin/site/get-apples",
            type: "post",
            data: {
                _csrf: yii.getCsrfToken(),
            },
            cache: false,
            success: function (responsData) {

                for (var i = 0; i < 12; i++) {
                    
                    var appleState = ""
                    if (responsData[i].state == 1) {
                        appleState = "Висит на дереве"
                    } else if (responsData[i].state == 2) {
                        appleState = "Лежит на земле"
                    } else if (responsData[i].state == 3) {
                        appleState = "Гнилое яблоко"
                    }
                     
                    $("#applesList").append(\'<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 card"><div class="apple-card"><div class="apple" id="\'+responsData[i].id+\'" style="background: \'+responsData[i].color+\';"></div><h3><button class="btn btn-sm btn-info" type="button" style="margin-right:2px;" onclick="fallenApple(\'+responsData[i].id+\')">Упасть</button><button class="btn btn-sm btn-primary" type="button" style="margin-right:2px;" onclick="eatenApple(\'+responsData[i].id+\')">Cъесть на</button><select class="input-sm" id="inputSelect\'+responsData[i].id+\'" style="padding:0px !important;"><option selected value="25">25%</option><option value="50">50%</option><option value="75">75%</option><option value="100">100%</option></select><div style="position: absolute; display: block; width: 180px; text-align: center; color: white; top:-150px;"><span id="sizeId\'+responsData[i].id+\'">\'+responsData[i].size+\'%</span><br><span  id="stateId\'+responsData[i].id+\'">\'+appleState+\'</span></div></h3></div></div>\');

                } 

            },

        });


    


    ',

    yii\web\View::POS_READY
);

?>