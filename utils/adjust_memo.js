define(['app','util','api'],function(app,util) {
	const ADJM ={UI:{}};

		// Adjustment Types
		ADJM.UI.TYPES = [
			{id:'AMFAV1', code:'FAV',name:'Financial Assistance Voucher'},
			{id:'AMFAV2', code:'AFAV',name:'Additional Financial Assistance Voucher'},
			{id:'AMVASF', code:'VSV',name:'Valedictorian Special Voucher'},
			{id:'AMSASV', code:'SSV',name:'Salutatorian Special Voucher'},
			{id:'AMTPSV', code:'TSV',name:'Top Special Voucher'},
			{id:'AMSFAV', code:'SFAV',name:'Special Financial Voucher'},
			{id:'AMSPOV', code:'SPV',name:'Sponsorship Voucher'},
			{id:'AMTMCA', code:'TBD',name:'Temporary Clear Account'},
			{id:'AMPECA', code:'PBD',name:'Permanent Clear Account'}
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