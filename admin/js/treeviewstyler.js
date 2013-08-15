/* 

	treeview Styler v0.1
	written by Alen Grakalic, provided by Css Globe (cssglobe.com)
	visit http://cssglobe.com/lab/treeview_styler/
	
*/

this.treeviewstyler = function(){
	var treeview = document.getElementById("treeview")
	if(treeview){
		
		this.listItem = function(li){
			if(li.getElementsByTagName("ul").length > 0){
				var ul = li.getElementsByTagName("ul")[0];
				ul.style.display = "block";
				var span = document.createElement("span");
				span.className = "collapsed";
				span.onclick = function(){
					ul.style.display = (ul.style.display == "none") ? "block" : "none";
					this.className = (ul.style.display == "none") ? "expanded" : "collapsed";
				};
				li.appendChild(span);
			};
		};
		
		var items = treeview.getElementsByTagName("li");
		for(var i=0;i<items.length;i++){
			listItem(items[i]);
		};
		
	};	
};

window.onload = treeviewstyler;
