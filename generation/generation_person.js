var personChartLoaded = false;

document.getElementById('loadPersonalStat').addEventListener('click', function() {
    if (!personChartLoaded) {
        const text5 = document.createElement("h4");
        text5.innerHTML = "Статистика личностных качеств учеников"
        document.getElementById('personalChartContainer').appendChild(text5);

        let chartFive = document.createElement("div");
        chartFive.id = 'chartFive';
        document.getElementById('personalChartContainer').appendChild(chartFive);

        let chartSix = document.createElement("div");
        chartSix.id = 'chartSix';
        document.getElementById('personalChartContainer').appendChild(chartSix);

        personChartLoaded = true;
        document.getElementById('hidePersonalStat').style.display = 'block';
        document.getElementById('personalChartContainer').style.display = 'flex';

        loadStudentsContainer();
        loadSkillsChart();
        const text6 = document.createElement("h4");
        text6.innerHTML = "Наиболее подходящие роли";
        document.getElementById('personalChartContainer').appendChild(text6);

        loadRoleChart();
    }
});

document.getElementById('hidePersonalStat').addEventListener('click', function() {
    document.getElementById('personalChartContainer').innerHTML = '';
    personChartLoaded = false;
    document.getElementById('hidePersonalStat').style.display = 'none';
    document.getElementById('personalChartContainer').style.display = 'none';
});

function loadStudentsContainer() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'generation/get_students_for_person.php', true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            var data = JSON.parse(xhr.responseText);
            if (data.length !== 0) {
                for (let i = 0; i < data.length; i++) {
                    const container = document.getElementById("chartFive");

                    const div = document.createElement("div");
                    div.id = data[i] + i;

                    const text = document.createElement("h4");
                    text.innerHTML = "Качества ученика " + data[i];

                    div.appendChild(text);
                    container.appendChild(div);
                }
            } else {
                const container = document.getElementById("chartFive");
                const errorText = document.createElement("h4");
                errorText.innerHTML = "Учеников недостаточно";
                container.appendChild(errorText);
            }
        } else {
            console.log("ERROR")
        }
    }
    xhr.send();
}

function loadSkillsChart() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'generation/get_students_skill.php', true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            if (xhr.responseText != "NO") {
                var data = JSON.parse(xhr.responseText);
                let i = 0;
                
                for (let key in data) {

                    if (data[key].length != 0) {
                        google.charts.load('current', {'packages':['corechart']});
                        google.charts.setOnLoadCallback(drawSkillsChart);
    
                        function drawSkillsChart() {
                            var chartData = data[key];
                            const dataArray = Object.entries(chartData).map(([label, value]) => ({ label, value }));
                            
                            const dataNew = new google.visualization.DataTable();
                            dataNew.addColumn('string', 'Label');
                            dataNew.addColumn('number', 'Value');
    
                            dataNew.addRows(dataArray.map(item => [item.label, item.value]));
    
                            var options = {
                                title: 'Качества ученика ' + key.replace(/[0-9]+$/, ''),
                                width: 300,
                                height: 200,
                                titleTextStyle: {
                                    color: 'white',
                                    fontSize: 15,
                                    bold: true,
                                    textAlign: 'center'
                                },
                                backgroundColor: 'none',
                                legend: {
                                    textStyle: {
                                        color: 'white'
                                    }
                                }
                            };
    
                            var chart = new google.visualization.PieChart(document.getElementById(key));
                            chart.draw(dataNew, options);
                        }
                    } else {
                        const errText = document.createElement("h4");
                        const br = document.createElement("br");
                        errText.innerHTML = "Недостаточно данных";
                        document.getElementById(key).appendChild(br);
                        document.getElementById(key).appendChild(errText);
                    }
                }
            } else {
                console.log('no');
            }
        }
    };
    xhr.send();
}

function loadRoleChart() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'generation/get_roles.php', true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            if (xhr.responseText != "NO") {
                let data = JSON.parse(xhr.responseText);

                google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(drawRoles);

                function drawRoles() {
                    // var chartData = new google.visualization.DataTable();
                    // chartData.addColumn('string', 'Имя');
                    // chartData.addColumn('number', 'Количество ролей');
                    // chartData.addColumn({type: 'string', role: 'tooltip', p: {'html': true}});

                    var chartData = new google.visualization.DataTable();
                    chartData.addColumn('string', 'Имя');
                    chartData.addColumn('number', 'Лидер');
                    chartData.addColumn('number', 'Мотиватор');
                    chartData.addColumn('number', 'Исполнитель');
                    chartData.addColumn({type: 'string', role: 'tooltip', p: {'html': true}});

                    var rowsData = [];
                    Object.keys(data).forEach(function(name) {
                        var roles = data[name].split(', ');
                        var rolesCount = roles.length;
                        var tooltipText = 'Роли: ' + roles.join(', ');
                        var rowData = [name, 0, 0, 0, tooltipText]; // Начальное значение для каждой роли

                        roles.forEach(function(role) {
                            if (role === 'lider') {
                                rowData[1] = 1; // Увеличиваем значение для роли 'lider'
                            } else if (role === 'motivator') {
                                rowData[2] = 1; // Увеличиваем значение для роли 'motivator'
                            } else if (role === 'executor') {
                                rowData[3] = 1; // Увеличиваем значение для роли 'executor'
                            } else if (role === 'coordinator') {
                                rowData[4] = 1;
                            }
                        });

                        rowsData.push(rowData);
                    });

                    chartData.addRows(rowsData);

                    var options = {
                        title: 'Распределение ролей по именам',
                        chartArea: {width: '50%'},
                        hAxis: {
                            title: 'Имя',
                            minValue: 0
                        },
                        vAxis: {
                            title: 'Количество ролей',
                            textPosition: 'none'
                        },
                        tooltip: {isHtml: true},
                        colors: ['#FF5733', '#33FF57', '#3333FF'] 
                    };

                    const chart = new google.visualization.ColumnChart(document.getElementById('chartSix'));
                    chart.draw(chartData, options);
                }
            } else {
                console.log(data);
            }
        }
    };
    xhr.send();
}