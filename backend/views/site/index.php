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

        <div class="row">

            <div class="col-xs-12" id="test">


            </div>


            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 card">

                <div class="apple-card">

                    <div class="apple"></div>
                    <h3>Apple #1</h3>
                
                </div>

            </div>


            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 card">

                <div class="apple-card">

                    <div class="apple"></div>
                    <h3>Apple #2</h3>
                
                </div>

            </div>


            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 card">

                <div class="apple-card">

                    <div class="apple"></div>
                    <h3>Apple #3</h3>
                
                </div>

            </div>


            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 card">

                <div class="apple-card">

                    <div class="apple"></div>
                    <h3>Apple #4</h3>
                
                </div>

            </div>

    

            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 card">

                <div class="apple-card">

                    <div class="apple"></div>
                    <h3>Apple #5</h3>
                
                </div>

            </div>


                       

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
                var testApples = JSON.stringify(responsData);
                $("#test").html(testApples);
            },

        });

        alert("ok");

    }
    
    ',

    yii\web\View::POS_HEAD
);

?>