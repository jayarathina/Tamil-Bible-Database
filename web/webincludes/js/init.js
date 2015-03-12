function initSettings(templateFolder) {
	
	templateFolder = templateFolder.split('/')[0] + '/';
	
	var pre = '';
	var scripts = document.getElementsByTagName('script');
	if (scripts.length > 0) {
	    var scriptsRef = scripts[0].src;
		if(scriptsRef.indexOf(templateFolder) > -1){
			var res = scriptsRef.split(templateFolder); 
			$pre = res[0] + templateFolder;
		}else{
			return;
		}
	}

	skel.init({
		reset: 'full',
		breakpoints: {
			global: {
				href: $pre + 'css/style.css',
				containers: 1400,
				grid: { gutters: ['2em', 0] }
			},
			xlarge: {
				media: '(max-width: 1680px)',
				href: $pre + 'css/style-xlarge.css',
				containers: 1200
			},
			large: {
				media: '(max-width: 1280px)',
				href: $pre + 'css/style-large.css',
				containers: 960,
				grid: { gutters: ['1.5em', 0] },
				viewport: { scalable: false }
			},
			medium: {
				media: '(max-width: 980px)',
				href: $pre + 'css/style-medium.css',
				containers: '90%'
			},
			small: {
				media: '(max-width: 736px)',
				href: $pre + 'css/style-small.css',
				containers: '90%',
				grid: { gutters: ['1.25em', 0] }
			},
			xsmall: {
				media: '(max-width: 480px)',
				href: $pre + 'css/style-xsmall.css',
			}
		},
		plugins: {
			layers: {
				config: {
					mode: 'transform'
				},
				navPanel: {
					animation: 'pushX',
					breakpoints: 'medium',
					clickToHide: true,
					height: '100%',
					hidden: true,
					html: '<div data-action="moveElement" data-args="nav"></div>',
					orientation: 'vertical',
					position: 'top-left',
					side: 'left',
					width: 250
				},
				navButton: {
					breakpoints: 'medium',
					height: '4em',
					html: '<span class="toggle" data-action="toggleLayer" data-args="navPanel"></span>',
					position: 'top-left',
					side: 'top',
					width: '6em'
				}
			}
		}
	});
}

$(window).load(function() {
	$(".loader").hide();
});