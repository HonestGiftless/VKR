var chartLoaded = false;

document.getElementById('loadChartButton').addEventListener('click', function() {
    if (!chartLoaded) {
        let studentContainer = document.createElement("div");
        let taskContainer = document.createElement("div");
        let gradesContainer = document.createElement("div");

        studentContainer.id = 'studentChart';
        taskContainer.id = 'taskChart';
        gradesContainer.id = 'gradeChart';

        document.getElementById('chartContainer').appendChild(studentContainer);
        document.getElementById('chartContainer').appendChild(taskContainer);
        document.getElementById('chartContainer').appendChild(gradesContainer);

        loadChart();
        loadTaskChart();
        loadGradeChart();

        chartLoaded = true;

        document.getElementById('chartContainer').style.display = 'flex';
    }
});

document.getElementById('hideChartButton').addEventListener('click', function() {
    document.getElementById('chartContainer').innerHTML = '';
    chartLoaded = false;
    document.getElementById('hideChartButton').style.display = 'none';
    document.getElementById('chartContainer').style.display = 'none';
});

function loadChart() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'generation/get_students_data.php', true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            if (xhr.responseText == "NO") {
                const div = document.getElementById("studentChart");
                const noneText = document.createElement("h4");
                noneText.innerHTML = "Данных недостаточно для отображения графика учеников";
                div.appendChild(noneText);
            } else {
                google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(drawChart);

                function drawChart() {
                    if (xhr.status === 200) {
                        var data = JSON.parse(xhr.responseText);

                        var charData = google.visualization.arrayToDataTable([
                            ['Распределение', 'Количество учеников'],
                            ['В группах', data.inGroup],
                            ['Без групп', data.notInGroup]
                        ]);

                        var options = {
                            title: 'График распределения учеников',
                            width: 400,
                            height: 240,
                            titleTextStyle: {
                                color: 'white',
                                fontSize: 18,
                                bold: true,
                                textAlign: 'center'
                            },
                            colors: ['green', 'darkred'],
                            backgroundColor: 'none',
                            legend: {
                                textStyle: {
                                    color: 'white'
                                }
                            }
                        };

                        var chart = new google.visualization.PieChart(document.getElementById('studentChart'));
                        chart.draw(charData, options);

                        document.getElementById('hideChartButton').style.display = 'block';
                    }
                }
            }
        }
    }
    xhr.send();
}

function loadTaskChart() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'generation/generation_tasks.php', true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            if (xhr.responseText == "NO") {
                const div = document.getElementById("taskChart");
                const noneText = document.createElement("h4");
                noneText.innerHTML = "Данных недостаточно для отображения графика заданий";
                div.appendChild(noneText);
            } else {
                google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(drawTaskChart);

                function drawTaskChart() {
                    if (xhr.status === 200) {
                        var data = JSON.parse(xhr.responseText);
                        
                        var charData = google.visualization.arrayToDataTable([
                            ['Распределение', 'Количество заданий'],
                            ['Групповые', data.groupTask],
                            ['Индивидуальные', data.notGroupTask]
                        ]);
        
                        var options = {
                            title: 'График распределения заданий',
                            width: 400,
                            height: 240,
                            titleTextStyle: {
                                color: 'white',
                                fontSize: 18,
                                bold: true,
                                textAlign: 'center'
                            },
                            colors: ['green', 'darkred'],
                            backgroundColor: 'none',
                            legend: {
                                textStyle: {
                                    color: 'white'
                                }
                            }
                        };

                        var chart = new google.visualization.PieChart(document.getElementById('taskChart'));
                        chart.draw(charData, options);
                    }
                }
            }
        }
    }
    xhr.send();
}

function loadGradeChart() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'generation/get_grades.php', true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            if (xhr.responseText == "NO") {
                const div = document.getElementById("gradeChart");
                const noneText = document.createElement("h4");
                noneText.innerHTML = "Данных недостаточно для отображения графика оценок";
                div.appendChild(noneText);
            } else {
                google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(drawGradeChart);

                function drawGradeChart() {
                    if (xhr.status === 200) {
                        var data = JSON.parse(xhr.responseText);

                        var charData = google.visualization.arrayToDataTable([
                            ['Распределение', 'Количество оценок'],
                            ['Оценка 1', data.one],
                            ['Оценка 2', data.two],
                            ['Оценка 3', data.three],
                            ['Оценка 4', data.four],
                            ['Оценка 5', data.five],
                        ]);

                        var options = {
                            title: 'График распределения оценок',
                            width: 400,
                            height: 240,
                            titleTextStyle: {
                                color: 'white',
                                fontSize: 18,
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

                        var chart = new google.visualization.PieChart(document.getElementById('gradeChart'));
                        chart.draw(charData, options);
                    }
                }
            }
        }
    }
    xhr.send();
}