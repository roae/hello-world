function __(key,stripTags){
	if(__L10n[key]){
		return (stripTags)? __stripTags(__L10n[key]) : __L10n[key];
	}
	return key;
}

function __stripTags(val){
	return val.replace(/<[^<]+?>/g,"");
}