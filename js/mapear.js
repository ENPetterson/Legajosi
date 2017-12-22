function mapear(domicilio, callback){
  $.ajaxSetup({async: false});
  var geocoder = new google.maps.Geocoder();
  
  
  var indice = domicilio.toLowerCase().indexOf("entre calles");
  if (indice == -1){      
      indice = domicilio.toLowerCase().indexOf("entre las calles");
  }
  
  if (indice > -1){
      domicilio = domicilio.substring(0, indice);
  }
  
  var buscar = {
      address: domicilio,
//    address: 'ALEM LEANDRO N. AV. 356 Piso:12 CIUDAD AUTONOMA BUENOS AIRES',
      componentRestrictions: {
          country: 'AR'
      }
  };
  var result;
  geocoder.geocode(buscar, function(result, status){
      if (status === google.maps.GeocoderStatus.OK) {
            result = {
                resultado: true,
                calle: result[0].address_components[1].long_name,
                numero: result[0].address_components[0].long_name,
                provincia: result[0].address_components[5].long_name,
                localidad: result[0].address_components[3].long_name,
                partido: result[0].address_components[4].long_name,
                geo: result[0].geometry.location.lat() + ',' + result[0].geometry.location.lng()
            }
            callback(result);
      }
  });
}