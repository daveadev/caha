define(['uni4ml/core-policy'],function($policy) {
	const U4ML ={};
	const DEFAULT={};
		  
		  DEFAULT.specialSubjs = $policy.specialSubjs;
		  DEFAULT.excludeFromCond = $policy.excludeFromCond;
		  DEFAULT.disableEntry = $policy.disableEntry;
		  DEFAULT.disablePrint = $policy.disablePrint;
		  DEFAULT.precisions =$policy.roundingOff;

		  DEFAULT.lastCol = 'TRGR';
		  DEFAULT.periods = {
		  					regular:['1st','2nd','3rd','4th'],
		  					special:['Midterm','Finals']
		  				};
	U4ML.__ = DEFAULT;
	U4ML.buildRecordbook=function(RBK,GCP){
		var COMPS = [];
		var SUBJ_ID = RBK.subject_id;
		var MCP = RBK.measurable_components;
		var MITS = RBK.measurable_items;
		var COMP = [];
		var SUBCOMP = {};
		var MIT = [];
		var SCP = [];
		var comp_labels = GCP;
		//GCP - General Component
		//SCP - Sub Component
		//MIT - Measurable Item
		//Loop Measurable Components		
		angular.forEach(MCP, function($mcp){
			var gcp_id = $mcp.general_component_id;
			var order = $mcp.order;					
			var perc = $mcp.percentage;
			var len = order.toString().length;
			if(len==1)
				order = '0'+order;
			var code = gcp_id+order;
			//Main Components
			if(!$mcp.under){
				var label = comp_labels[gcp_id] + ' ' +perc + '%';
				COMP[gcp_id] = [gcp_id,{code:code,label: label,percentage:perc,order:order},[]];
			}
			//Sub Components
			if($mcp.under){
				SUBCOMP[$mcp.id] = [gcp_id,{code:code,label: gcp_id,percentage:perc,order:order,mcp_id:$mcp.id},[]];
				SCP[$mcp.id] = $mcp;
			}
		});
		//Loop into Measurable Items
		for(var index in MITS){
			var mit = MITS[index];
			var isEnabled =  !mit.classroom_coursework_id;
			var desc = mit.description;
			if(!isEnabled)
				desc += ' from Google Classroom';
			var order = mit.order;
			var o = order.toString().length;
			if(o==1)
				order = '0'+order;
			MIT[index] = [mit.header,{label: mit.header, 
									  address_code: mit.header + order,
									  order: mit.order,
									  items:mit.items,
									  desc:desc,
									  mit_id:mit.id,
									  precision:'none',
									  enable: isEnabled,
									  validations:{max:mit.items,allow_ignore:true}
									}];
		}

		
		//Append MIT to corresponding SUBCOMP
		for(var index in MIT){
			var mit = MIT[index];
			var att = MITS[index];
			SUBCOMP[att.measurable_component_id][2].push(mit);
		}
		//Add SUM Column per SCP
		
		for(var i in SUBCOMP){
			var scp = SUBCOMP[i];
			var scp_attr = scp[1];
			var children =  scp[2];
			var addr_codes = [];
			var $ADRC =  scp_attr.code;
			var $ORDR =  scp_attr.order;
			var $PRCT = scp_attr.percentage;
			
			var $SUM_ITMS = 0;
			var $EQ_ROU = U4ML.__.precisions.EQUIV;
			var $WG_ROU = U4ML.__.precisions.WGHT;
			//Map children address codes
			for(var j in children){
				var child = children[j];
				var attr =  child[1];
				$SUM_ITMS+=attr.items;
				addr_codes.push(attr.address_code);
			}

			//Add Special Columns when more than one addr_codes
			if(addr_codes.length>1){
				var range_criteria = addr_codes[0]+':'+addr_codes[addr_codes.length-1];
				//Total Column Object
				var $TOT_OBJ ={
					address_code:'SUM'+$ADRC,
					items:$SUM_ITMS,
					role:'total',
					formula:"SUMIF(" + range_criteria+", \"!='IGN'\" ) "
				};
				var total_col = ['Total',$TOT_OBJ];
				children.push(total_col);
				// Build Points Unique Id (PUI) Codes
				var pui_codes = [];
				
				for(var _i in addr_codes){
					pui_codes.push('PUI'+addr_codes[_i]);
				}
				var range_sum = pui_codes[0]+':'+pui_codes[pui_codes.length-1];
				//Equivalent Computation Qualifier Column Object
				var $ECQ_SUM ="SUMIFS(" +  range_sum + ","+ range_criteria+", \"!='IGN'\" ) "
				$SUM_ITMS =  $ECQ_SUM;

				//Equivalent Object for multiple items
				var $EQV_OBJ={
					label:'Equiv',
					address_code:'EQV'+$ADRC,
					precision:$EQ_ROU,
					role:'equiv',
					formula:"ROUND(" + $TOT_OBJ.address_code+"/"+$SUM_ITMS + "* 100" + ", "+$EQ_ROU+" ) "
				};
				
			}else{
				//Equivalent Object for single item
				var $EQV_OBJ={
					label:'Equiv',
					address_code:'EQV'+$ADRC,
					precision:$EQ_ROU,
					role:'equiv',
					formula:"ROUND(" + addr_codes[0]+"/"+$SUM_ITMS + "* 100" + ", "+$EQ_ROU+" )"
				};
			}
			var equiv_col = ['E'+$ORDR,$EQV_OBJ];
				children.push(equiv_col);

			if($PRCT!=100){
				var $WGT_OBJ = {
					address_code:'WGT'+$ADRC,
					label:$PRCT+'%',
					precision:$WG_ROU,
					//rowspan:2,
					role:'wght',
					formula:"ROUND(" + $EQV_OBJ.address_code + "*"+$PRCT+" /100 ,"+$WG_ROU+" ) "
				}
				var wght_col = ['W'+$ORDR,$WGT_OBJ];
					children.push(wght_col);
			}
			SUBCOMP[i][2]=children;
		}
		
		var mit_ctr = 0;
		RBK.measurable_map={};
		
		//Append SCP to corresponding GCP
		for(var index in SUBCOMP){
			var scp = SUBCOMP[index];
			var attr = SCP[index];
			scp[1]['label'] = scp[0];
			for(var i in scp[2]){
				var order = scp[2][i][1]['order'];
				if(order){
					var o = order.toString().length;
					var key =RBK.subject_id;
					if(o==1)
						order = '0'+order;
					var cod = attr.under + '~' + attr.general_component_id + order;
					
					scp[2][i][1]['code']=cod;
					scp[2][i][1]['key']=key;
					
					var meas_id = MITS[mit_ctr].id;
					var cwrk_id = MITS[mit_ctr].classroom_coursework_id;

					if(cwrk_id){
						RBK.measurable_map[cwrk_id]= {'code':cod,'meas_id':meas_id};
					}
					mit_ctr++;	
					
				}
			}
			COMP[attr.under][2].push(scp);
		}
		
		var comp_codes=[];
		var ovrall_objs = [];
		var ovrall_total = 0;
		//Traverse each GCP
		for(var index in COMP){
			var comp = COMP[index];	
			var addr = comp[1].code;
			var perc = comp[1].percentage;
			var wght = perc/100;
			
			var children =  comp[2];
			var comp_sum = [];
			//Loop into each SCP weight address_codes
			for(var j in children){
				var child = children[j];
				var chl_obj =  child[1];
				var wght_adr = 'WGT'+chl_obj.code;
				if(chl_obj.percentage==100)
					wght_adr = 'EQV'+chl_obj.code;
				console.log(wght_adr,chl_obj);
				comp_sum.push(wght_adr);
			}
			comp_sum =  'ROUND(SUM('+comp_sum.join(',')+'),'+$WG_ROU+')';
			
			//Special indices for Overall and Percentage
			var oall_code = 'OA'+index;
			var prad_code = 'PR'+index;
			ovrall_total += perc;
			//Build oall_object
			var oall_obj = [
				oall_code,
				{code:oall_code, label:comp[0],role:"warning"},
				[
				  [
				  prad_code,
				  {
				  	address_code:prad_code,
				  	label:perc+'%',
				  	formula:comp_sum+'*'+wght,
				  	precision:2,
				  	role:"warning"
				  }
				  ]
				]
			];

			comp_codes.push(prad_code);
			ovrall_objs.push(oall_obj);
			COMPS.push(comp);
		}

		// Create Total Object to sum all GCP weights
		var $OA_ROU = U4ML.__.precisions.OVRALL;
		var $OA_OBJ = ['OA1',
						{code:'TOT',label:'TOTAL',role:"warning"},
						[
							['OVRALL',
							{
								address_code:'OVRALL',
								label:ovrall_total+'%',
								precision:2,
								role:'oall warning',
								formula: 'ROUND(SUM('+comp_codes.join(',')+'),' + 2 + ')'
							}
							]
						]
					];
		ovrall_objs.push($OA_OBJ);
		
		var $FG_OBJ = ['TRGR',
						{
							address_code:'TRGR',
							label:'TRANS GRADE',
							precision:$OA_ROU,
							role:'qtrg warning',
							formula: 'IF(OVRALL>=60,ROUNDDOWN((OVRALL-60)/1.6,0)+75,ROUNDDOWN((OVRALL/4)+60,0))',

						},
					];
		if(U4ML.isSpecialSubj(SUBJ_ID)){
			$FG_OBJ[1].label = 'FINAL GRADE';
			$FG_OBJ[1].formula = 'IF(OVRALL<60,60,OVRALL)';
		}
		// OVERRIDE: DepEd Memo s.2020 no.042  Apr 20,2020
		if(RBK.esp>=2019.3&&ovrall_total<100){
			$FG_OBJ[1].formula = "IF(OVRALL>=42.5,ROUNDDOWN((OVRALL-42.5)/1.1,0)+75,IF(OVRALL>=5.71,ROUNDDOWN((OVRALL-5.71)/2.83,0)+62,IF(OVRALL>=2.88,61,60)))";
			$FG_OBJ[1].precision = "none";
			$FG_OBJ[1].override = function(val){
				val = parseFloat(val);
				const ROUNDDOWN = rjs.formulas.ROUNDDOWN
				const SIGVAL = 5; // Significant Value
				if(val>=42.5){
					var num = parseFloat(((val-42.5)/1.1).toFixed(SIGVAL));
					return ROUNDDOWN(num,0)+75;
				}else if(val>=5.71){
					var num = parseFloat(((val-5.71)/2.83).toFixed(SIGVAL));
					return ROUNDDOWN(num,0)+62;
				}else if(val>=2.88)
					return 61;
				else
					return 60;

			} 
		}
		ovrall_objs.push($FG_OBJ);
		
		var oa_col = ['OVERALL',{code:'OAL1',label:"SUMMARY",order:"05", role:"warning"},ovrall_objs];
		COMPS.push(oa_col);

		return COMPS;
	}

	U4ML.buildCombinedCol = function(dept,period,subjId){
		var FNRT,$AV_ROU = U4ML.__.precisions.AVG;
		switch(dept){
			case 'SH':
				
				prevQtrLabel = U4ML.__.periods.special[0];
				currQtrLabel = U4ML.__.periods.special[1];
				
				var finrte_objs = [
					['PRVRTE',{code:'PRVQ',address_code:'PRVQ',
							key:subjId,
							label:prevQtrLabel,enable:false}],
					['CURRTE',{address_code:'CURQ',label:currQtrLabel,formula:'TRGR'}],
					['SEMGRD',{address_code:'SEMGRD',label:'Sem. Grade',role:'qtrg',
							formula:'AVERAGE(PRVQ,CURQ)',precision:$AV_ROU}],
				];
				FNRT =  ['FINRTE', {code:'FNRTE',label:"FINAL RATING", order:"07"},finrte_objs];
			break;
			default:
				var finrte_objs = [];
				var qtrCodes = [];
				var codes = ['FRSTGRDG','SCNDGRDG','THRDGRDG','FRTHGRDG'];

				for(var n=1;n<=period;n++){
					var code =  codes[n-1];
					var addr =  code;
					var qtrLabel = U4ML.__.periods.regular[n-1]
					var colObj =  [addr,{code:code,address_code:addr,key:subjId,label:qtrLabel,enable:false,role:'qtrg'}];
					if(n==period)
						colObj[1].formula='TRGR';
					
					qtrCodes.push(addr);
					finrte_objs.push(colObj);
		
				}
				var avgCol = ['FINGRD',{address_code:'FINGRD',label:'Avg.',role:'qtrg',
								formula:'AVERAGE('+qtrCodes.join()+')',precision:$AV_ROU}]
				finrte_objs.push(avgCol);

				FNRT =  ['FINRTE', {code:'FNRTE',label:"Quarterly Grades", order:"07"},finrte_objs];
			break;
		}
		console.log(FNRT);
		return FNRT;
	}
	U4ML.buildRubric =function(RBX,SUBJ_ID){
		var input =  RBX.rubrics.teachers.input;
		var items =  RBX.rubrics.teachers.items;
		var $CO_ROU = input.finites.precision;
		var $CO_FRM = input.formula;
		var cndct_objs = [];
		var cndct_codes = [];
		var order = 0;
		//Create rubric object
		for(var i in items){
			var item = items[i];
			var order = parseInt(i) +1;
			var rbx_code = item.header;
			var rbx_desc = item.description;
			var rbx_addr = item.header + order;
			var rbx_obj = 
				[item.header,
					{
						label: item.header, 
						code: item.header,
						address_code: rbx_addr,
						order: order,
						items:input.finites.max,
						desc:item.description,
						saveas:'conduct',
						rbi_id:item.id,
						precision:$CO_ROU,
						key:SUBJ_ID,
						validations:{max:input.finites.max, min:input.finites.min}
				}];
			cndct_objs.push(rbx_obj);
			cndct_codes.push(rbx_addr);
		}
		var $CO_TOT =['CONTOT',
							{
								address_code:'CONTOT',
								label:'Total',
								precision:$CO_ROU,
								role:'total',
								formula: 'ROUND(SUM('+cndct_codes.join(',')+'),' + $CO_ROU + ')'
							}
					];

		var $CO_AVG = ['CONAVG',
							{
								address_code:'CONAVG',
								label:'Avg',
								precision:$CO_ROU,
								role:'avg',
								formula: 'ROUND(SUM('+cndct_codes.join(',')+')/'+cndct_codes.length+',' + $CO_ROU + ')'
							}
					];
		var $CO_EQV = ['CONEQV',
							{
								address_code:'CONEQV',
								label:'Conduct',
								precision:'none',
								formula:
										'IF(CONAVG<=1.75,"NO",'+
										'IF(CONAVG<=2.50,"RO",'+
										'IF(CONAVG<=3.25,"SO",'+
										'IF(CONAVG<=4.00,"AO",""'+
										')))',
								override:function(val){
									val = parseFloat(val);
									if(val<=1.75) return "NO";
									if(val<=2.50) return "RO";
									if(val<=3.25) return "SO";
									if(val<=4.00) return "AO";
									return "";
								}
								
							}
					];
		//cndct_objs.push($CO_TOT);
		cndct_objs.push($CO_AVG);
		cndct_objs.push($CO_EQV);
		var COND = ['CONDUCT',{code:'CND1',label:"CONDUCT",order:"06"},cndct_objs];
		
		var rprtcard_objs = [
					['CG',{code:'CRDG',label:'CARD GRADE',formula:'TRGR'}],
					['CC',{code:'CRDC',label:'CARD CONDUCT','precision':'none',formula:'CONEQV',override:function(val){ return val;}}],
		];
		var REPO = ['REPORTCARD',{code:'RC1',label:"REPORT CARD",order:"06"},rprtcard_objs];
		
		return COND;
	}

	U4ML.isSpecialSubj=function(subjId){
		var isSpecial = U4ML.__.specialSubjs.indexOf(subjId)!=-1;
		return	isSpecial;
	}
	U4ML.excludeFromConduct =  function(subjId){
		var isExcluded = U4ML.__.excludeFromCond.indexOf(subjId)!=-1;
		return	isExcluded;
	}
	U4ML.isDisableEntry = function(period,subjId){
		var isDisabled  = false;
		if(U4ML.__.disableEntry[period])
			isDisabled = U4ML.__.disableEntry[period].indexOf(subjId)!=-1;
		return isDisabled;
	}
	U4ML.isDisablePrint = function(period){
		var isDisabled  = false;
		if(U4ML.__.disablePrint[period])
			isDisabled = U4ML.__.disablePrint[period].indexOf(subjId)!=-1;
		return isDisabled;	
	}
	U4ML.allowReadOnlyBy = function(userType){
		var isAllowed = false;
			isAllowed = U4ML.__.recordbook.readOnlyBy.indexOf(userType)!=-1;
		return isAllowed;
	}

	U4ML.lastCol = DEFAULT.lastCol;

	U4ML.implement = function(schoolPolicy){
		for(var policy in schoolPolicy){
			var rule = schoolPolicy[policy];
			U4ML.__[policy] = rule;
		}
	}
	return U4ML;
	
});