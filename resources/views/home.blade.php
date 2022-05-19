<h1>Homepage</h1>
<h2>Открытое API для получения расписания <a href="https://stankin.ru">Станкина</a></h2>
<p>Узлы:</p>
<ul>
    <li>
        /byWeek/{group}/{subgroup}
        - расписание на неделю. <a href="{{route('byWeek', ['ИДБ-20-05', 'А'])}}">Пример запроса</a>
    </li>
    <li>
        /byDay/{group}/{subgroup}/{day}
        - расписание на день. <a href="{{route('byDay', ['ИДБ-20-05', 'А', 'Среда'])}}">Пример запроса</a>
    </li>
    <li>
        /all_groups
        - все доступные группы. <a href="{{route('all_groups')}}">Пример запроса</a>
    </li>
</ul>

