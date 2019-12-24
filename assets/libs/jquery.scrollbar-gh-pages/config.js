$(function()
{
	$('.scrollbar-inner').scrollbar();
	$('.scrollbar-outer').scrollbar();
	$('.scrollbar-macosx').scrollbar();
	$('.scrollbar-light').scrollbar();
	$('.scrollbar-rail').scrollbar();
	$('.scrollbar-dynamic').scrollbar();
	$('.scrollbar-chrome').scrollbar();
	$('.textarea-scrollbar').scrollbar();

	$('.scrollbar-vista').scrollbar(
	{
		"showArrows": true,
		"scrollx": "advanced",
		"scrolly": "advanced"
	});
	$('.scrollbar-external').scrollbar(
	{
		"autoScrollSize": false,
		"scrollx": $('.external-scroll_x'),
		"scrolly": $('.external-scroll_y')
	});
	//##############################################################################
	//##############################################################################
	/**
	 * Get inscribed area size
	 *
	 * @param int oW outer width
	 * @param int oH outer height
	 * @param int iW inner width
	 * @param int iH inner height
	 * @param bool R resize if smaller
	 */
	function getInscribedArea(oW, oH, iW, iH, R){
		if(!R && iW < oW && iH < oH){
			return {
				"h": iH,
				"w": iW
			};
		}
		if((oW / oH) > (iW / iH)){
			return {
				"h": oH,
				"w": Math.round(oH * iW / iH)
			}
		} else {
			return {
				"h": Math.round(oW * iH / iW),
				"w": oW
			};
		}
	}
	$('.scrollbar-map').scrollbar(
	{
		"onInit": function(){
			this.container.find('.scroll-element_outer').appendTo(this.wrapper);
		},
		"onUpdate": function(container){
			var s = getInscribedArea(140, 140, this.scrollx.size, this.scrolly.size);
			this.scrolly.scroll.height(s.h);
			this.scrollx.scroll.width(s.w);
		},
		"scrollx": $('.scrollbar-map .scroll-element_outer'),
		"scrolly": $('.scrollbar-map .scroll-element_outer'),
		"stepScrolling": false
	});
	$('.scrollbar-janos').scrollbar(
	{
		"showArrows": true,
		"scrollx": "advanced",
		"scrolly": "advanced"
	});
});