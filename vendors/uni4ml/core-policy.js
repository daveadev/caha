define(function() {
	const  POLICY ={};
	POLICY.specialSubjs = [];
	POLICY.excludeFromCond = POLICY.specialSubjs;
	POLICY.disableEntry = {2:POLICY.specialSubjs,4:POLICY.specialSubjs};
	POLICY.disablePrint = {};
	POLICY.roundingOff = {EQUIV:2,WGHT:2,OVRALL:0,AVG:0};
	POLICY.recordbook = {
		readOnlyBy:['offcr'],
	}
	return POLICY;
 });