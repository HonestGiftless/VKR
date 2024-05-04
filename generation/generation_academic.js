var academChartLoaded = false;

document.getElementById('loadAcademStat').addEventListener('click', function() {
    if (!academChartLoaded) {
        // Создание контейнеров для чартов и добавление на страницу
        let chartOne = document.createElement("div");
        chartOne.id = 'chartOne';
        document.getElementById('academChartContainer').appendChild(chartOne);

        let chartSec = document.createElement("div");
        chartSec.id = 'chartSec';
        document.getElementById('academChartContainer').appendChild(chartSec);

        let chartThree = document.createElement("div");
        chartThree.id = 'chartThree';
        document.getElementById('academChartContainer').appendChild(chartThree);

        // Создание текста для индивидуальной статистики
        const text = document.createElement("h4");
        text.innerHTML = "Статистика по индивидуальным заданиям";
        chartSec.appendChild(text);

        // Создание дочерних элементов
        const individualTaskContainer = document.createElement("div");
        individualTaskContainer.id = "indiv_task";
        chartSec.appendChild(individualTaskContainer);

        const text3 = document.createElement("h4");
        text3.innerHTML = "Статистика по групповым заданиям";
        chartThree.appendChild(text3);

        const groupTaskContainer = document.createElement("div");
        groupTaskContainer.id = "group_task";
        document.getElementById('chartThree').appendChild(groupTaskContainer);

        academChartLoaded = true;
        document.getElementById('academChartContainer').style.display = 'flex';

        // Вызов функций для отображения графиков студентов
        loadStudents();
        loadGeneralChart();
        loadTasks();
        loadGroupsTaskChart();
    }
});

document.getElementById('hideAcademStat').addEventListener('click', function() {
    document.getElementById('academChartContainer').innerHTML = '';
    academChartLoaded = false;
    document.getElementById('hideAcademStat').style.display = 'none';
    document.getElementById('academChartContainer').style.display = 'none';
});

function loadStudents() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'generation/get_students_for_grade.php', true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            var data = JSON.parse(xhr.responseText)
            if (data.length !== 0) {
                for (let i = 0; i < data.length; i++) {
                    const container = document.getElementById("chartOne");

                    const div = document.createElement("div");
                    div.id = data[i] + i;

                    const text = document.createElement("h4");
                    text.innerHTML = "Оценки ученика " + data[i];

                    div.appendChild(text);
                    container.appendChild(div);
                }
            } else {
                console.log('Пока нет учеников')
            }
        }
    }
    xhr.send();
    document.getElementById('hideAcademStat').style.display = 'block';
}

function loadGeneralChart() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'generation/get_academ_stat.php', true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            var response = xhr.responseText;
            var data = JSON.parse(response);
            let i = 0;


            for (let key in data) {
                if (data.hasOwnProperty(key)) {
                    const container = document.getElementById(key + i);

                    if (data[key] != '') {
                        const container = document.getElementById(key + i);

                        google.charts.load('current', {'packages':['corechart']});
                        google.charts.setOnLoadCallback(drawAcademicChart);

                        function drawAcademicChart() {
                            var chartData = data[key];
                            var charData = google.visualization.arrayToDataTable([
                                ['Распределение', 'Количество оценок'],
                                ['Оценка 1', chartData.one],
                                ['Оценка 2', chartData.two],
                                ['Оценка 3', chartData.three],
                                ['Оценка 4', chartData.four],
                                ['Оценка 5', chartData.five]
                            ]);

                            var options = {
                                title: 'Оценки ученика ' + key,
                                width: 300,
                                height: 200,
                                titleTextStyle: {
                                    color: 'white',
                                    fontSize: 15,
                                    bold: true,
                                    textAlign: 'center'
                                },
                                colors: ['#ba2e2e', '#ba4a2e', '#BAA52E', '#9CBA2E', '#2EBA2E'],
                                backgroundColor: 'none',
                                legend: {
                                    textStyle: {
                                        color: 'white'
                                    }
                                }
                            };
                            var chart = new google.visualization.PieChart(container);
                            chart.draw(charData, options);
                        }
                        
                    } else {
                        const trn = document.createElement("br");
                        const txt = document.createElement("h4");
                        txt.innerHTML = "Данных недостаточно";

                        container.appendChild(trn);
                        container.appendChild(txt);
                    }
                    i++;
                }
    
            }
        }
    }
    xhr.send();
    document.getElementById('hideAcademStat').style.display = 'block';
}

function loadTasks() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'generation/generation_tasks_stat.php', true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            if (xhr.responseText != "NO") {
                const container = document.getElementById('indiv_task');
                let data = JSON.parse(xhr.responseText);

                let allNames = new Set();
                for (let key in data) {
                    for (let name in data[key]) {
                        allNames.add(name);
                    }
                }

                let chartData = [['Name', ...allNames]];

                for (let key in data) {
                    let row = [key];
                    for (let name of allNames) {
                        row.push(data[key][name] || 0);
                    }
                    chartData.push(row);
                }

                google.charts.load('current', { packages: ['corechart'] });
                google.charts.setOnLoadCallback(ddt);

                function ddt() {
                    var data = google.visualization.arrayToDataTable(chartData);
                    var options = {
                        title: 'Выполненные задания',
                        legend: { position: 'top' },
                        hAxis: {
                            textPosition: 'none'
                        }
                    };
    
                    var chart = new google.visualization.Histogram(container);
                    chart.draw(data, options);
                }
            } else {
                const container = document.getElementById('indiv_task');
                const trn = document.createElement("br");
                const txt = document.createElement("h4");
                txt.innerHTML = "Данных недостаточно";

                container.appendChild(trn);
                container.appendChild(txt);
            }
        }
    };
    xhr.send();
}

function loadGroupsTaskChart() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'generation/generation_group_tasks_stat.php', true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            if (xhr.responseText != "NO") {
                const container = document.getElementById("group_task");
                let data = JSON.parse(xhr.responseText);

                let allGroups = new Set();
                for (let key in data) {
                    for (let name in data[key]) {
                        allGroups.add(name);
                    }
                }

                let chartData = [['Name', ...allGroups]];

                for (let key in data) {
                    let row = [key];
                    for (let name of allGroups) {
                        row.push(data[key][name] || 0);
                    }
                    chartData.push(row);
                }

                google.charts.load('current', { packages: ['corechart'] });
                google.charts.setOnLoadCallback(ggtc);

                function ggtc() {
                    var data = google.visualization.arrayToDataTable(chartData);

                    var options = {
                        title: 'Выполненные задания',
                        legend: { position: 'top' },
                        hAxis: {
                            textPosition: 'none'
                        }
                    };

                    var chart = new google.visualization.Histogram(container);
                    chart.draw(data, options);
                }
            } else {
                const container = document.getElementById("group_task");
                const trn = document.createElement("br");
                const txt = document.createElement("h4");
                txt.innerHTML = "Данных недостаточно";

                container.appendChild(trn);
                container.appendChild(txt);
            }
        }
    }
    xhr.send();
}