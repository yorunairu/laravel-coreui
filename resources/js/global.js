import $ from 'jquery';
console.log($); // Should log the jQuery function
import select2 from 'select2';
select2();
import 'summernote';
import 'datatables.net-bs5';
import 'datatables.net-autofill-bs5';
import 'datatables.net-buttons-bs5';
import 'datatables.net-datetime';
import 'datatables.net-fixedcolumns-bs5';
import 'datatables.net-fixedheader-bs5';
import 'datatables.net-responsive-bs5';
import 'datatables.net-rowgroup-bs5';
import 'datatables.net-scroller-bs5';
import JSZip from 'jszip'; // If you need to use JSZip directly
import pdfMake from 'pdfmake/build/pdfmake'; // If you need to use pdfmake directly
import pdfFonts from 'pdfmake/build/vfs_fonts'; // Required for pdfmake

// Attach jQuery to the window object
window.$ = $;
window.jQuery = $;

$(document).ready(function() {
    console.log('jQuery loaded in custom.js');
    console.log('Input event triggered');
});
