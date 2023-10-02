const getFormattedDate  = (date) => {
    let year = date.getFullYear();
    let month = (1 + date.getMonth()).toString().padStart(2, '0');
    let day = date.getDate().toString().padStart(2, '0');
  
    return month + '/' + day + '/' + year;
}

const FormatRupiah = (bilangan, prefix) => {
	var	number_string = bilangan.toString(),
	split	= number_string.split(','),
	sisa 	= split[0].length % 3,
	rupiah 	= split[0].substr(0, sisa),
	ribuan 	= split[0].substr(sisa).match(/\d{1,3}/gi),
	separator
	;
		
	if (ribuan) {
		separator = sisa ? '.' : '';
		rupiah += separator + ribuan.join('.');
	}
	rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;

	return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
}

export {
	getFormattedDate,
	FormatRupiah
}