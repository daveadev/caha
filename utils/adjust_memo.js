define(['app','util','api'],function(app,util) {
	const ADJM ={UI:{}};

		// Adjustment Types
		ADJM.UI.TYPES = [
			{id:'AMFAV1', code:'FAV',name:'Financial Assistance Voucher'},
			{id:'AMLESC', code:'L-ESC',name:'Late Credit ESC/Voucher'},
			{id:'AMRESC', code:'RegESC',name:'Regular Credit ESC/Voucher'},
			{id:'AMFAV2', code:'AFAV',name:'Additional Financial Assistance Voucher',disabled:true},
			{id:'AMOTFA', code:'OTFA',name:'One Time Financial Assistance'},
			{id:'AMVASF', code:'VSV',name:'Valedictorian Special Voucher',disabled:true},
			{id:'AMSASV', code:'SSV',name:'Salutatorian Special Voucher',disabled:true},
			{id:'AMTPSV', code:'TSV',name:'Top Special Voucher',disabled:true},
			{id:'AMSFAV', code:'SFAV',name:'Special Financial Voucher',disabled:true},
			{id:'AMSPOV', code:'SPV',name:'Sponsorship Voucher'},
			{id:'AMTMCA', code:'TBD',name:'Temporary Clear Account',applyToPaysched:false},
			{id:'AMPBDT', code:'PBD',name:'Permanent Bad Debt',applyToPaysched:false},
			{id:'AMREFU', code:'Refund',name:'Refund Account',applyToPaysched:false, adjustType:'fee'}
		];

		// Ledger Entry
		ADJM.UI.LEDGER = {};
		ADJM.UI.LEDGER.Headers = [{label:'Transact Date',class:'col-md-2'},'Ref No', 'Description',{label:'Fees',class:'text-right'},{label:'Payment',class:'text-right'},{label:'Balance',class:'text-right'}];
		ADJM.UI.LEDGER.Properties = ['date','ref_no','description','fee','payment','balance'];

		// Payment Schedule
		ADJM.UI.PAYMENT_SCHED={};
		ADJM.UI.PAYMENT_SCHED.Headers = [{label:'Due Date',class:'col-md-2 text-right'},{label:'Due Amount',class:'text-right'},{label:'Paid Amount',class:'text-right'},{label:'Balance',class:'text-right'},'Status'];
		ADJM.UI.PAYMENT_SCHED.Properties = ['due_date','due_amount','paid_amount','balance','status'];


	return ADJM;
});