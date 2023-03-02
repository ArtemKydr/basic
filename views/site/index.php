<?php
$css = <<<CSS

.blue{
background: #0069FF; 
border: 10px solid #0069FF; 
border-radius: 40px; 
width: 64%;
height: 230px; 
margin-top: 20px; 
padding: 28px;
color: white;
}
.green{
position: relative;
background: #BFFF00; 
border: 10px solid #BFFF00; 
border-radius: 40px; 
width: 64%;
height: 230px; 
margin-top: -12px;
margin-left: 40%;
padding: 28px 64px 28px 40px;
}
.black{
border: 2px solid #000000;
border-radius: 40px;
width: 240px;
height: 80px;
margin-left: -36%;
padding: 0px 64px 28px 40px;
}
.btn {
position: absolute;
right: 0;
width: 200px;
height: 40px;
display: inline-block; /* Строчно-блочный элемент */
background: #FFFFFF;
color: black;/* Серый цвет фона *//* Белый цвет текста */ /* Поля вокруг текста */
text-decoration: none; /* Убираем подчёркивание */
border-radius: 40px; /* Скругляем уголки */
border: 2px solid #000000;
}
.btn:hover {
background: #D8D8D8;
}
.dark-blue-squad{
box-sizing: border-box;

position: absolute;
width: 114px;
height: 220px;
left: 51px;
top: 822px;

border: 4px solid #0069FF;
border-radius: 40px;
}
.dark-green-squad{
box-sizing: border-box;

position: absolute;
width: 369px;
height: 137px;
left: 72px;
top: 725px;

border: 4px solid #BFFF00;
border-radius: 40px;
}
.blue-squad{
box-sizing: border-box;

position: absolute;
width: 130px;
height: 420px;
left: 1836px;
top: 128px;

border: 4px solid #00CCFF;
border-radius: 40px;
}
.green-squad{
box-sizing: border-box;

position: absolute;
width: 246px;
height: 153px;
left: 1774px;
top: 260px;

border: 4px solid #BFFF00;
border-radius: 40px;
}
.p-blue{
font-weight: 400;
font-size: 24px;
}
@media screen and (max-width: 480px) {

.dark-blue-squad{
box-sizing: border-box;

position: absolute;
width: 102px;
height: 42px;
left: 0px;
top: 921px;

border: 2px solid #0069FF;
border-radius: 40px;
transform: rotate(-90deg);
}
.dark-green-squad{
box-sizing: border-box;

position: absolute;
width: 248px;
height: 42px;
left: 51px;
top: 935px;

border: 2px solid #BFFF00;
border-radius: 40px;
}
.blue-squad{
box-sizing: border-box;

position: absolute;
width: 130px;
height: 420px;
left: 361px;
top: 135px;

border: 2px solid #00CCFF;
border-radius: 40px;
}
.green-squad{
box-sizing: border-box;

position: absolute;
width: 246px;
height: 153px;
left: 308px;
top: 180px;

border: 2px solid #BFFF00;
border-radius: 40px;
}
    .p-blue {
        font-size: 16px !important;
    }
        .p-green {
        font-size: 12px!important;
    }
    .blue{
    width: 100%;
    padding: 5px 10px;
    margin-left: 0%;
    }
    .green{
    width: 100%;
    padding: 5px 10px;
    margin-left: 0%;
    }
    .h4-green{
    font-size: 18px!important;
    }
    h1{
    font-size: 24px !important;
    }
    .black{
    width: 267px;
    height: 32px;
    margin-left: -16%;
    margin-top: 32%;
    padding-top: 4px;
    }
    .p-black{
    font-size: 16px !important;
    width: 100%;
    }
}
@media screen and (max-width: 820px) and (min-width: 481px) {
.dark-blue-squad{
box-sizing: border-box;

position: absolute;
width: 102px;
height: 42px;
left: 0px;
top: 835px;

border: 2px solid #0069FF;
border-radius: 40px;
transform: rotate(-90deg);
}
.dark-green-squad{
box-sizing: border-box;

position: absolute;
width: 248px;
height: 42px;
left: 51px;
top: 844px;

border: 2px solid #BFFF00;
border-radius: 40px;
}
.blue-squad{
box-sizing: border-box;

position: absolute;
width: 130px;
height: 420px;
left: 339px;
top: 180px;

border: 2px solid #00CCFF;
border-radius: 40px;
}
.green-squad{
box-sizing: border-box;

position: absolute;
width: 246px;
height: 153px;
left: 308px;
top: 180px;

border: 2px solid #BFFF00;
border-radius: 40px;
}

    .p-blue {
        font-size: 16px !important;
    }
        .p-green {
        font-size: 12px!important;
    }
    .blue{
    width: 100%;
    padding: 5px 10px;
    margin-left: 0%;
    }
    .green{
    width: 100%;
    padding: 5px 10px;
    margin-left: 0%;
    }
    .h4-green{
    font-size: 18px!important;
    }
    h1{
    font-size: 24px !important;
    }
    .black{
    width: 267px;
    height: 32px;
    margin-left: 20%;
    margin-top: 32%;
    padding-top: 4px;
    }
    .p-black{
    font-size: 16px !important;
    width: 100%;
    }
}
.ref{
color: white;
background: linear-gradient(to left, #0069ff, #f9dd94 100%);
background-position: 0 100%;
background-size: 100% 2px;
background-repeat: repeat-x;
}
@media screen and (max-width: 1350px) and (min-width: 821px) {
.p-blue{
font-weight: 400;
font-size: 20px;
}
}
    


CSS;
$this->registerCss($css);
$this->title ='ПОДАЧА МАТЕРИАЛОВ ПО ИТОГАМ НАУЧНЫХ МЕРОПРИЯТИЙ ИТМО';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class ='site-index' style="display: flex; flex-direction: column">
    <div class="green-squad">
    </div>
    <div class="blue-squad">
    </div>
    <h1>ПОДАЧА МАТЕРИАЛОВ ПО ИТОГАМ<br> НАУЧНЫХ МЕРОПРИЯТИЙ ИТМО</h1>
    <div class="blue">
        <p class = "p-blue">
            Как узнать актуальные <a class="ref" href="https://docs.google.com/document/d/1fNIDKphzYuISFFnWjXrqT4yeSjknbDmJeNYKAFJFIbo/edit">требования</a> по оформлению материала?<br>
            Как <a class="ref" href="https://docs.google.com/document/d/1fNIDKphzYuISFFnWjXrqT4yeSjknbDmJeNYKAFJFIbo/edit">зарегистрировать</a> свой личный кабинет?<br>
            Как <a class="ref" href="https://docs.google.com/document/d/1fNIDKphzYuISFFnWjXrqT4yeSjknbDmJeNYKAFJFIbo/edit">подать</a> материал для публикации?<br>
        </p>
    </div>
    <div class="green">
        <h4 class="h4-green" style="font-weight: bold; margin-bottom: 4px" align="right">Альманах научных работ молодых ученых Университета ИТМО</h4>
        <p class="p-green" style="font-size: 14px;" align="right">Пятьдесят вторая (LII) научная и учебно-методическая конференция Университета ИТМО</p>
        <a href="site/requirements" class="btn">Подробнее...</a>

        <div class="black">
            <p class="p-black" align="right" style="font-weight: 600; font-size: 24px;">
                Подача
                до 15 марта
            </p>
        </div>
    </div>
    <div class="dark-green-squad">
    </div>
    <div class="dark-blue-squad">
    </div>
</div>

