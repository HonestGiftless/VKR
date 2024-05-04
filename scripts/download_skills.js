function downloadSkillsFile(studentId) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'download.php?student_id=' + studentId, true);
    xhr.responseType = 'blob';

    xhr.onload = function() {
        if (this.status === 200) {
            var blob = new Blob([xhr.response], { type: 'application/octet-stream' });
            var url = URL.createObjectURL(blob);
            var a = document.createElement('a');
            a.href = url;
            a.download = 'skills.txt';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        }
    };

    xhr.send();
}


function downloadNotesFile(studentId) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'download_notes.php?student_id=' + studentId, true);
    xhr.responseType = 'blob';

    xhr.onload = function() {
        if (this.status === 200) {
            var blob = new Blob([xhr.response], { type: 'application/octet-stream' });
            var url = URL.createObjectURL(blob);
            var a = document.createElement('a');
            a.href = url;
            a.download = 'notes.txt';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        }
    };

    xhr.send();
}