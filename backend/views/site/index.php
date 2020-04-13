<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">

        <h1>Apples</h1>

        <button class="btn btn-lg btn-success" onclick="generateApplesList()">Generate Apples</button>

    </div>

    <div class="body-content">

        <div class="row" id="test">

        </div>

    </div>

</div>


<?php

$this->registerJs(
    '

    function generateApplesList(){
        $.ajax({
            url: "site/generate-apples",
            type: "post",
            data: {
                _csrf: yii.getCsrfToken(),
            },
            cache: false,
            success: function (responsData) {

                $("#test").html("");
                responsData.forEach(obj => {
                    Object.entries(obj).forEach(([key, value]) => {
                        // console.log(`${key} ${value}`);
                        if ( key == "id") {
                            $("#test").append(\'<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 card"><div class="apple-card"><div class="apple"></div><h3>Apple \'+`${value}`+\'</h3</div></div>\');
                        }
                        
                    });
                });

            },

        });

    }
    
    ',

    yii\web\View::POS_HEAD
);


$this->registerJs(
    '

        $.ajax({
            url: "site/get-apples",
            type: "post",
            data: {
                _csrf: yii.getCsrfToken(),
            },
            cache: false,
            success: function (responsData) {

                responsData.forEach(obj => {
                    Object.entries(obj).forEach(([key, value]) => {
                        if ( key == "id") {
                            $("#test").append(\'<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 card"><div class="apple-card"><div class="apple"></div><h3>Apple \'+`${value}`+\'</h3</div></div>\');
                        }
                        
                    });
                });

            },

        });

    ',

    yii\web\View::POS_READY
);

?>