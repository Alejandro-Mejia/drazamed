[[ recuperar Categorias de los Medicamentos ]]

URL : https://drazamed.devel/medicine/load-medicine-cats/1
REQUEST Type : GET
PARAMETROS : isWeb (1 si se llama desde un browser, 0 si se hace desde una app (-desarrollo futuro-))

Respuesta: JSON ordenado por orden alfabetico que contiene unicamente el valor de grupo

[{"result":{"status":"sucess","msg":[{"group":"ALOPECIA"},{"group":"ANALGESICOS"},{"group":"ANESTESICOS"},{"group":"ANSIOLITICOS"},{"group":"ANTIACIDOS"},{"group":"ANTIALERGICOS"},{"group":"ANTIANEMICOS"},{"group":"ANTIARRITMICOS"},{"group":"ANTIARTRITICOS"},{"group":"ANTIASMATICOS"},{"group":"ANTIBIOTICOS"},{"group":"ANTICOAGULANTES"},{"group":"ANTICONCEPTIVOS"},{"group":"ANTIDEPRESIVOS"},{"group":"ANTIDIABETICOS"},{"group":"ANTIDIARREICOS"},{"group":"ANTIEMETICOS"},{"group":"ANTIEPILEPTICOS"},{"group":"ANTIESPASMODICOS"},{"group":"ANTIFLATULENTOS"},{"group":"ANTIGOTOSOS"},{"group":"ANTIGRIPALES"},{"group":"ANTIHELMINTICOS"},{"group":"ANTIHEMORRAGICOS"},{"group":"ANTIHEMORROIDES"},{"group":"ANTIHIPERTENSIVOS"},{"group":"ANTIHISTAMINICOS"},{"group":"ANTIINFLAMATORIOS"},{"group":"ANTIMICOTICOS"},{"group":"ANTINEURITICOS"},{"group":"ANTIPARASITARIOS"},{"group":"ANTIPSICOTICOS"},{"group":"ANTIRREUMATICOS"},{"group":"ANTISEPTICOS"},{"group":"ANTITUSIVOS"},{"group":"ANTIULCEROSOS"},{"group":"ANTIVARICOSOS"},{"group":"ANTIVERTIGINOSOS"},{"group":"ANTIVIRALES"},{"group":"BRONCODILATADORES"},{"group":"CANCER"},{"group":"CARDIOVASCULARES"},{"group":"CICATRIZANTES"},{"group":"COLESTEROL"},{"group":"COMPLEMENTO NUTRICIONAL"},{"group":"CORTICOIDES INHALANTES"},{"group":"CORTICOSTEROIDES"},{"group":"CUIDADO ORAL"},{"group":"CUIDADO PIEL"},{"group":"DERMATOLOGICOS"},{"group":"DESCONGESTIONANTES"},{"group":"DISFUNCION ERECTIL"},{"group":"EMOLIENTES Y PROTECTORES"},{"group":"EQUIPODIAG.Y ACCESORIOS"},{"group":"ESCABICIDAS"},{"group":"EXPECTORANTES"},{"group":"HEMOSTATICO"},{"group":"HEPATICOS"},{"group":"HIPERPLASIA"},{"group":"HIPOLIPEMIANTES"},{"group":"HORMONAS"},{"group":"INCONTINENCIA"},{"group":"INMUNOSUPRESOR"},{"group":"LAXANTES"},{"group":"LINEA HOSPITALARIA"},{"group":"NEUROTONICOS"},{"group":"NOOTROPICOS"},{"group":"OFTALMOLOGICOS"},{"group":"OSTEOPOROSIS"},{"group":"OTICOS"},{"group":"PARKINSON"},{"group":"PRIMEROS AUXILIOS"},{"group":"PRODUCTOS DIETETICOS"},{"group":"PRODUCTOS NATURALES"},{"group":"QUERATOLITICOS"},{"group":"REGULADORES DIGESTIVOS"},{"group":"RUBEFACIENTES"},{"group":"SALIVA ARTIFICIAL"},{"group":"SUEROS"},{"group":"TIROIDES"},{"group":"UROLOGICOS"},{"group":"VASODILATADORES"},{"group":"VITAMINAS"}]}}]



[[ Busqueda  general de Medicamentos ]]

URL(ejemplo) : https://drazamed.devel/medicine/search-medicine/1?term=glaxo&limit=20
REQUEST Type : GET
PARAMETROS : isWeb (1 si se llama desde un browser, 0 si se hace desde una app (-desarrollo futuro-))
             term= termino de busqueda (Extraee conicidencias con los campos item-name, marketed-by, composition, group)
             limit= numero limite de resultados (por default 4)


Ejemplo de salida
[{"result":{"status":"sucess","msg":[{"id":1,"item_code":"7707172680056","name":"AMOXAL 500 MG 15 CAPSULAS ","mrp":"5048","lab":"GLAXO SMITHKLINE COLOMBIA","composition":"AMOXICILINA"},{"id":2,"item_code":"7707172680346","name":"AMOXAL 500 MG 30 CAPSULAS","mrp":"9669","lab":"GLAXO SMITHKLINE COLOMBIA","composition":"AMOXICILINA"}]}}


