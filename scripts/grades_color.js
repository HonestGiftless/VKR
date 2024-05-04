const table = document.getElementById("scroll_table");

if (table) {
    let cells = table.getElementsByTagName('a');

    for (let i = 0; i < cells.length; i++) {
        let link = cells[i];
        
        let rating = parseInt(link.textContent || link.innerText);
        
        if (rating == 5) {
            link.classList.add('five_color');
        } else if (rating == 4) {
            link.classList.add('four_color');
        } else if (rating == 3) {
            link.classList.add('three_color');
        } else if (rating == 2) {
            link.classList.add('two_color');
        } else if (rating == 1) {
            link.classList.add('one_color');
        }
    }
}