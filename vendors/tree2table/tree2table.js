define(function() {
	const MAX_COLUMN = 17;
	const MAX_LEVEL =  3;
	//Sanitize list to generate a 2D array
	function sanitize(list){
		
		list = normalize(legalize(list));

		var arr = [];
		//Update array index
		for (var index in list) {
			arr[arr.length] = list[index];
		}


		//Reverse array to start traversal on leaf nodes
		arr =  arr.reverse();
		
		//Start on second to the last level
		for(var p=1;p<arr.length;p++){
			var ptr = 0;
			var lvl = arr[p];
			//Traverse on elements to compute for colspan
			for(var i=0;i<lvl.length;i++){
				var parent =  lvl[i];
				if(parent.child){
					var c = p - 1;
					var chld = arr[c];
					//console.log(chld, parent.code,parent.id);
					//Starting traversing based on current pointer and limit to child count
					
					for(var j=ptr,ctr=1;ctr<=parent.child&&j<chld.length;j++){
						var child = chld[j];
						//var _parent = arr[p][i];
						
						//Check node if related
						if(parent.id==child.parent){
							var hash =  i;
							//console.log(p,i,c,j);
							//Colspan computation
							if(arr[p][i].colspan == undefined)
								arr[p][i].colspan = 0;
							
							if(child.child){
								if(child.colspan==undefined){
									arr[p][i].colspan+=child.child;
								}else{
									
									arr[p][i].colspan+=child.child;
								}
							}else{
								arr[p][i].colspan = arr[p][i].child;
							}
							parent = arr[p][i];
							ctr++;
						}
					}
					
					
					//Update pointer base on child count minus 1
					ptr = parent.child - 1;
					if(arr[p][i].colspan == undefined)
						arr[p][i].colspan =  parent.colspan;
				}
			}
		}
		//Reverse to display correctly
		arr =  arr.reverse();
		//Compute for rowspan
		var len = arr.length;
		for(var index=0; index<len;index++){
			var lvl = arr[index];
			for(var i=0;i<lvl.length;i++){
				if(lvl[i].child==undefined){
					arr[index][i].rowspan = arr[index][i].rowspan||len - index;
				}
				if(lvl[i].leaf){
					//Pad right path to increase value
					arr[index][i].path = pad(lvl[i].path,len+1,'0',2);
				}
			}
		}
		// String padding utility function
		function pad(str, len, pad, dir) {
			const STR_PAD_LEFT = 1;
			const STR_PAD_RIGHT = 2;
			const STR_PAD_BOTH = 3;

			if (typeof(len) == "undefined") { var len = 0; }
			if (typeof(pad) == "undefined") { var pad = ' '; }
			if (typeof(dir) == "undefined") { var dir = STR_PAD_RIGHT; }

			if (len + 1 >= str.length) {

				switch (dir){

					case STR_PAD_LEFT:
						str = Array(len + 1 - str.length).join(pad) + str;
					break;

					case STR_PAD_BOTH:
						var right = Math.ceil((padlen = len - str.length) / 2);
						var left = padlen - right;
						str = Array(left+1).join(pad) + str + Array(right+1).join(pad);
					break;

					default:
						str = str + Array(len + 1 - str.length).join(pad);
					break;

				} // switch

			}

			return str;

		}
		// 	Attach parent id to self and add reference path for nesting
		function legalize(nodes,parent,level,path){
			//Initialize values
			level =  level || 1;
			parent =  parent || {id:0,parent:null}; //Root parent as default
			path =  path || ''; 
			for(var key in nodes){
				var node = nodes[key];
				if(node[1]){
					var N1 = node[1];
					if(!N1.length){
						N1.id = key;
						N1.parent = parent.id;
						path = path + parent.id;
						 //Trim path when length is longer than level
						if(path.length>level)
							path = path.slice(0,-1);
						N1.path =  path+key;
					}
				}
				if(node[2]){
					var N2 = node[2];
					//Call legalize recursively
					node[2] = legalize(N2,N1,level+1,path);
				}
			}
			
			return nodes;
		}
		function normalize(items,level,list,parent,ctr){
			// Initialize default values
			level = level || 0;
			list = list || [];
			parent = parent || null;
			ctr = ctr || 0;
			switch(typeof items){
				case 'object':
					if(items.length) {
						//Increment level to update index
						level++;
						for(var index in items){
							//Recursively call normalize to crawl list
							normalize(items[index],level,list,parent,ctr);
							if(typeof items[index][1] == 'object'){
								if(typeof items[index][2] == 'object'){
									//Append child count for nodes with children
									var len = items[index][2].length;
									if(len) items[index][1]['child'] = len;
								}else{
									//Add leaf node flag
									 items[index][1]['leaf'] =true;
								}
							} 
						}			
					}
					else {
						//Update labeling
						var index = list[level].length-1;
						items.label = items.label || list[level][index].label;
						list[level][index] = items;
						
					}
				break;
				case 'string':
					//Push node label
					if(list[level]==undefined)
						list[level]=[];
					var item = {label:items};
					list[level].push(item);
				break;
			}
			return list;
		}
		//Return sanitized array
		return arr;
	}
	//Extract leaf nodes given tree
	function extractLeaves(tree){
		var leaves = [];
		var keys = [];
		var indices={};
		var nodes = {};
		var level = 1;
		for(var i in tree){
			for(var j in tree[i]){
				var node =  tree[i][j];
				//Collect all leaf nodes and paths
				if(node.leaf){
					var n = node;
					var k = parseInt(n.path)* (Math.pow(10,MAX_LEVEL-level));
					if(level>1)
					var j = parseInt(n.parent)* (Math.pow(10,MAX_LEVEL-(level-1)));
						k =  k+j;
					keys.push(k);
					indices[k] = n.path;
					nodes[n.path] = n;
				}
			}
			level++;
		}
		//Sort keys
		keys.sort(function(a, b){return a-b});
		
		//Retrieve keys and push nodes as sorted leaves
		for(var index in keys){
			var i = keys[index];
			var key =  indices[i];
			leaves.push(nodes[key]);
		}
		return leaves;
	}
	
	function transform(list){
		var header 	= sanitize(list);
		var body	= extractLeaves(header);
		var buffer = [];
		var len = header[header.length-1].length;
		var blen = body.length;
		if(len<MAX_COLUMN){
			for(var i=1;i<=MAX_COLUMN-len;i++){
				buffer.push(null);
			}
		}
		var table = {
			thead:header,
			tbody:body,
			buffer:buffer
		};
		return table;
	}
	return {
		transform:transform
	}
 });