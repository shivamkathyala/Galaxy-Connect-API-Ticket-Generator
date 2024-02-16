document.addEventListener('DOMContentLoaded', function() {
function exportHTMLtoPDF() {
    let htmlElement = document.getElementById('content');

    let opt = {
        margin:       0.5,
        filename:     'galaxy_ticket.pdf',
        image:        { type: 'jpeg', quality: 1 },
        html2canvas:  { scale: 1 },
        jsPDF:        { unit: 'in', format: 'letter', orientation: 'landscape' }
      };
      html2pdf().set(opt).from(htmlElement).save(); 

}

 document.getElementById('download-ticket').addEventListener('click', exportHTMLtoPDF);

});