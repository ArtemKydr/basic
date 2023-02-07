<?php
$css =<<<CSS

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
@media screen and (max-width: 480px) {
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
    


CSS;
$this->registerCss($css);
?>
<div class ='site-index' style="display: flex; flex-direction: column">

    <h1>ПОДАЧА МАТЕРИАЛОВ ПО ИТОГАМ<br> НАУЧНЫХ МЕРОПРИЯТИЙ ИТМО</h1>
    <div class="blue">
        <p class = "p-blue"style="font-weight: 400;font-size: 24px;">
            Как узнать актуальные требования по оформлению материала?<br>
            Как зарегистрировать свой личный кабинет?<br>
            Как подать материал для публикации?<br>
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
</div>

