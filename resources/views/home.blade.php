<h1>Homepage</h1>
<h2>Открытое API для получения расписания <a href="https://stankin.ru">Станкина</a></h2>
<p>Маршруты:</p>
<ul>
    <li>
        <b>/student/byWeek/{group}/{subgroup}</b>
        - расписание на неделю для студента. <a href="{{route('student_byWeek', ['ИДБ-20-05', 'А'])}}">Пример запроса</a>
    </li>
    <li>
        <b>/student/byDay/{group}/{subgroup}/{day}</b>
        - расписание на день для студента. <a href="{{route('student_byDay', ['ИДБ-20-05', 'А', 'Среда'])}}">Пример запроса</a>
    </li>
    <li>
        <b>/teacher/byWeek/{teacher}</b>
        - расписание на неделю преподавателя. <a href="{{route('teacher_byWeek', ['Елисеева_Н.В.'])}}">Пример запроса</a>
    </li>
    <li>
        <b>/teacher/byDay/{teacher}/{day}</b>
        - расписание на день преподавателя. <a href="{{route('teacher_byDay', ['Елисеева_Н.В.', 'Среда'])}}">Пример запроса</a>
    </li>
    <li>
        /all_groups
        - все доступные группы студентов. <a href="{{route('all_groups')}}">Пример запроса</a>
    </li>
    <li>
        /all_teachers
        - ФИО преподавателей, расписание которых доступно. <a href="{{route('all_teachers')}}">Пример запроса</a>
    </li>
</ul>
<p>
    Для маршутов, выделенных жирных шрифтом, возможно использование фильтра "subject_filter", передаваемого в виде GET-параметра.
    Данный фильтр принимает одно из следующих значений:
</p>
<ul>
    <li>laboratory - отобразить только лабораторные работы</li>
    <li>seminar - отобразить только семинары</li>
    <li>lecture - отобразить только лекции</li>
</ul>

