<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'Требования';
$this->params['breadcrumbs'][] = $this->title;

$css =<<<CSS
.container{
max-width: 1920px !important;
padding: 0px 340px 0px 340px;
}
.navbar.navbar-expand-md.navbar-dark.bg-dark.fixed-top.navbar{
display: block;
width: 100%;
}
.navbar-nav.nav{
width: 900px;
}

CSS;
$this->registerCss($css);
?>

<div>
    <div class="requirements" style="display: flex; justify-content: center;flex-direction: column; align-items: center">
        <div>
            <h4>ТРЕБОВАНИЯ К ОФОРМЛЕНИЮ МАТЕРИАЛА</h4>
        </div>
        <div>
            <H4>Проверка оригинальности проводится <b>до 9 марта 2023</b>, прием статей - <b>до 15 марта 2023</b></H4>
        </div>
    </div>
    <br>
    <div>
        <ol>
            <li>Подготовить статью согласно <a href="\web\Templates\Требования к статье Альманах 2023.docx">Требованиям</a>.
            <li>Пройти регистрацию на сайте и зайти в свой <a href="/sign-up">Личный кабинет</a>.
            <li>Загрузить и отправить статью на проверку на оригинальность в ЛК. Срок рассмотрения до 3 рабочих дней. На проверку необходимо отправлять окончательный вариант статьи. Опубликовывается именно файл, прошедший проверку на оригинальность. Пороговое значение 70 %.
            <li>Для проверки статьи есть 2 попытки. При двухкратном отклонении статьи по причине недостаточного порога оригинальности материал не принимается для печати в сборнике.
            <li>После УСПЕШНОЙ проверки статьи статус заявки меняется на <i>“Статья прошла проверку на оригинальность”.</i> Далее необходимо ЗАГРУЗИТЬ комплект документов:
                <ul>
                    <li>полный скан файла статьи с подписями автора и научного руководителя (формат *pdf/*jpeg) - подписи не должны быть на отдельной странице, обязательно внизу страницы после текста статьи или располагаться около фамилий в начале.
                    <li>файл подписанного / согласованного экспертного заключения (ЭЗ) (формат *pdf/*jpeg). <a href="\web\Templates\ExpertInstr.pdf">Инструкция</a>
                    <li>шаблон экспертного заключения для сторонних участников. <a href="\web\Templates\zakliuchenie-o-vozmozhnosti-otkrytogo-opublikovaniia.docx">Скачать шаблон</a>
                </ul>
                <b>Для студентов из ИТМО ЭЗ подается через личный кабинет ИСУ.</b>
            <li> Если статья НЕ ПРОШЛА  проверку на оригинальность, то статус заявки изменится на <i>“Статья не прошла проверку на оригинальность”</i> и будет предоставлена ВТОРАЯ (ПОСЛЕДНЯЯ) попытка сдачи статьи.
            <li> Рецензия на статью. Рецензентом может быть научный сотрудник как ИТМО, так и сторонних организаций, являющийся специалистом в данной научной области. Рецензентом НЕ может быть научный руководитель, соавтор или человек, не имеющий ученой степени. <a href="\web\Templates\Шаблон рецензия.docx">Скачать шаблон</a>
            <li>После загрузки и ОТПРАВКИ полного комплекта документов статус изменится на <i>“Статья на рассмотрении”.</i>
            <li>После проверки оргкомитетом комплектности заявки статус изменится на <i>“Статья принята к рецензированию”.</i> В данном случае необходимо дожидаться проверки рецензента и после успешной проверки статус заявки изменится на <i>“Статья принята к публикации”.</i>

        </ol>
    </div>
    <br>
    <div class="status-description" >
        <div>
            <h5>ПОДРОБНО О СТАТУСАХ ЗАЯВКИ</h5>
        </div>
    </div>
    <br>
    <div>
        <ul>
            <li><b>Статья направлена на проверку</b> - присваивается после отправки файла статьи для проверки на оригинальность (антиплагиат) и соответствии оформлению материала требованиям.
            <li><b>Статья не прошла проверку на оригинальность</b> -присваивается после проверки материала на оригинальность и получении порогового значения МЕНЬШЕ 70%.
            <li><b>Статья прошла проверку на оригинальность</b> - присваивается после проверки на оригинальность и получении порогового значения БОЛЬШЕ 70% и соответствии требованиям оформления материала.

            <li><b>Статья не соответствует требованиям</b> - при загрузке документов, не соответствующим указанным требованиям.

            <li><b>Статья отклонена, так как неполный комплект документов</b> - направлен неполный комплект документов.

            <li><b>Статья на рассмотрении</b> - присваивается после успешного прохождения проверки на оригинальность и отправки полного комплекта материалов (прикрепления всех файлов).
            <li><b>Статья принята к рецензированию</b> - после успешной проверки комплекта материалов оргкомитетом.

            <li><b>Статья принята к публикации</b> - после успешной проверки рецензентами материалов сборника, данный статус свидетельствует о том, что статья будет опубликована в Сборнике трудов.



        </ul>
    </div>
</div>
