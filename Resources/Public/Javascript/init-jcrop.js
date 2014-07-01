$(function() {
	
	var jcrop_api,
		boundx,
		boundy,
		
		preview = $('#preview-pane'),
		pcnt    = $('#preview-pane .preview-container'),
		pimg    = $('#preview-pane .preview-container img'),
		
		xsize = pcnt.width(),
		ysize = pcnt.height();
	
	var scaledPercent = $('#scaledPercent').val(),
			warnMinSize   = 0,
			selPre        = $('#preset'),
			option,
			targetW,
			targetH;
	
	
	// Preview funktion
	function updatePreviewAndCoords(c) {
		if (parseInt(c.w) > 0) {
			var rx = xsize / c.w;
			var ry = ysize / c.h;
			
			pimg.css({
				width: Math.round(rx * boundx) + 'px',
				height: Math.round(ry * boundy) + 'px',
				marginLeft: '-' + Math.round(rx * c.x) + 'px',
				marginTop: '-' + Math.round(ry * c.y) + 'px'
			});
		}
		
		var cW = Math.ceil(c.w);
		var cH = Math.ceil(c.h);
		
		$('#x1').val(Math.ceil(c.x));
		$('#y1').val(Math.ceil(c.y));
		$('#x2').val(Math.ceil(c.x2));
		$('#y2').val(Math.ceil(c.y2));
		$('#w').val(cW);
		$('#h').val(cH);
		
		if ( warnMinSize == 1 ) {
			var curOrigW = Math.ceil((cW / scaledPercent) * 100);
			var curOrigH = Math.ceil((cH / scaledPercent) * 100);
			
			if ( (curOrigW < targetW) || (curOrigH < targetH) ) {
				$('#warnMinSize:hidden').show();
			} else $('#warnMinSize:visible').hide();
		}
	};
	
	function enableJcrop(ratio) {
		$('#target').Jcrop({
			onChange: updatePreviewAndCoords,
			onSelect: updatePreviewAndCoords,
			aspectRatio: ratio
		},function(){
			// Use the API to get the real image size
			var bounds = this.getBounds();
			boundx = bounds[0];
			boundy = bounds[1];
			
			// Store the API in the jcrop_api variable
			jcrop_api = this;
			
			// Move the preview into the jcrop container for css positioning
			if ( !$(jcrop_api.ui.holder).find(preview).html() ) preview.appendTo(jcrop_api.ui.holder);
		});
		
		preview.draggable().css('display','none');
	}
	
	
	
	// First run
	enableJcrop(xsize / ysize);
	
	
		
	// Cancel cropping
	$('#cancel').on('click',function() {
		window.location.href = $('#referer').val();
	});
		
	// Enable preview
	$('#showPreview').on('click',function() {
		if ( preview.css('display') == 'none' ) {
			preview.css('display','inline-block');
		} else preview.css('display','none');
	});
	
	// Enable aspect ratio
	$('#aspectRatio').on('click',function() {
		if ( $('#preset').val() == "-1" ) {
			if ( $(this).prop('checked') ) {
				jcrop_api.setOptions({
					aspectRatio: xsize/ysize
				});
				
				$('#showPreview').parent().show();
			} else {
				jcrop_api.setOptions({
					aspectRatio: 0
				});
				
				$('#showPreview').parent().hide();
				$('#showPreview').attr('checked',false);
				preview.css('display','none');
			}
		}
	});
	
	// Preset Selector
	selPre.on('change',function() {
		option = $(this).find('option[value=' + $(this).val() + ']');
		
		targetW = option.data('width');
		targetH = option.data('height');
		
		var overlay     = option.data('overlay');
		var w           = option.data('scaledwidthpreview');
		var h           = option.data('scaledheightpreview');
		var disableFN   = option.data('disablefilename');
		var overwriteFN = option.data('overwritefile');
		
		// Resize
		$(pcnt,pimg).css({
			width  : w,
			height : h
		});
		
		pcnt.find('#overlay').css({
			backgroundImage: 'url(../../../../' + overlay +')',
			width: w,
			height: h,
			backgroundSize: w + 'px ' + h + 'px'
		});
		
		xsize = pcnt.width(),
		ysize = pcnt.height();
		
		// Disable / Enable Aspect Ratio Button
		if ( $(this).val() != "-1" ) {
			$('#aspectRatio').parent().hide();
			$('#showPreview').parent().show();
		} else {
			$('#aspectRatio').parent().show();
		}
		
		// Set aspect ratio of selection
		if ( ($(this).val() == "-1" && $('#aspectRatio').prop('checked')) || ($(this).val() != "-1") ) {
			jcrop_api.setOptions({
				aspectRatio: xsize/ysize
			});
		} else {
			jcrop_api.setOptions({
				aspectRatio: 0
			});
		}
		
		if ( disableFN == 1 ) {
			$('#newFilename').parent().hide();
		} else $('#newFilename').parent().show();
		
		if ( overwriteFN == 1 ) {
			$('#overwriteFile').parent().hide();
		} else $('#overwriteFile').parent().show();
		
		if ( option.data('warnminsize') == 1 ) {
			warnMinSize = 1;
		} else {
			warnMinSize = 0;
			$('#warnMinSize:visible').hide();
		}
	});
});