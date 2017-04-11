var neuelight = {
    src: '../sifr/flash/neuelight.swf'
};
sIFR.activate(neuelight);

sIFR.replace(neuelight, {
    selector: 'h1',
	       css: [
	       '.sIFR-root { font-size:39px; color:#282828; }',
		   '.funfacts { color: #a35a2f; }'	
	      ],     wmode: 'transparent'});


sIFR.replace(neuelight, {
	selector: 'h2',
      css: [
      '.sIFR-root { font-size:26px; color:#002b54; }',	
     ],     wmode: 'transparent'});


