<h1>Homepage</h1>
<h2>Открытое API для получения расписания <a href="https://stankin.ru">Станкина</a></h2>
<p>Узлы:</p>
<ul>
    <li>
        /student/byWeek/{group}/{subgroup}
        - расписание на неделю для студента. <a href="{{route('student_byWeek', ['ИДБ-20-05', 'А'])}}">Пример запроса</a>
    </li>
    <li>
        /student/byDay/{group}/{subgroup}/{day}
        - расписание на день для студента. <a href="{{route('student_byDay', ['ИДБ-20-05', 'А', 'Среда'])}}">Пример запроса</a>
    </li>
    <li>
        /teacher//byWeek/{teacher}
        - расписание на неделю преподавателя. <a href="{{route('teacher_byWeek', ['Елисеева_Н.В.'])}}">Пример запроса</a>
    </li>
    <li>
        /teacher//byDay/{teacher}/{day}
        - расписание на день преподавателя. <a href="{{route('teacher_byDay', ['Елисеева_Н.В.', 'Среда'])}}">Пример запроса</a>
    </li>
    <li>
        /all_groups
        - все доступные группы студентов. <a href="{{route('all_groups')}}">Пример запроса</a>
    </li>
    <li>
        /all_teachers
        - фамилии преподавателей, расписание которых доступно. <a href="{{route('all_teachers')}}">Пример запроса</a>
    </li>
</ul>

