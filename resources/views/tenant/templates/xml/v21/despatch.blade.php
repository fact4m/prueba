{!! '<?xml version="1.0" encoding="utf-8" standalone="no"?>' !!}
<DespatchAdvice xmlns="urn:oasis:names:specification:ubl:schema:xsd:DespatchAdvice-2"
				xmlns:ds="http://www.w3.org/2000/09/xmldsig#"
				xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2"
				xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2"
				xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2">
	<ext:UBLExtensions>
		<ext:UBLExtension>
			<ext:ExtensionContent/>
		</ext:UBLExtension>
	</ext:UBLExtensions>
	<cbc:UBLVersionID>2.1</cbc:UBLVersionID>
	<cbc:CustomizationID>1.0</cbc:CustomizationID>
	<cbc:ID>{{$document->series.'-'.$document->number}}</cbc:ID>
	<cbc:IssueDate>{{ $document->date_of_issue->format('Y-m-d') }}</cbc:IssueDate>
	<cbc:IssueTime>{{ $document->time_of_issue }}</cbc:IssueTime>
	<cbc:DespatchAdviceTypeCode>{{ $document->document_type_code }}</cbc:DespatchAdviceTypeCode>
    {% if doc.observacion -%}
	<cbc:Note><![CDATA[{{ doc.observacion|raw }}]]></cbc:Note>
    {% endif -%}
    {% if doc.docBaja -%}
	<cac:OrderReference>
		<cbc:ID>{{ doc.docBaja.nroDoc }}</cbc:ID>
		<cbc:OrderTypeCode listAgencyName="PE:SUNAT" listName="SUNAT:Identificador de Tipo de Documento" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo01">{{ doc.docBaja.tipoDoc }}</cbc:OrderTypeCode>
	</cac:OrderReference>
    {% endif -%}
    {% if doc.relDoc -%}
	<cac:AdditionalDocumentReference>
		<cbc:ID>{{ doc.relDoc.nroDoc }}</cbc:ID>
		<cbc:DocumentTypeCode listAgencyName="PE:SUNAT" listName="SUNAT:Identificador de documento relacionado" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo21">{{ doc.relDoc.tipoDoc }}</cbc:DocumentTypeCode>
	</cac:AdditionalDocumentReference>
    {% endif -%}
    {% set emp = doc.company -%}
	<cac:DespatchSupplierParty>
		<cbc:CustomerAssignedAccountID schemeID="6">{{ emp.ruc }}</cbc:CustomerAssignedAccountID>
		<cac:Party>
			<cac:PartyLegalEntity>
				<cbc:RegistrationName><![CDATA[{{ emp.razonSocial|raw }}]]></cbc:RegistrationName>
			</cac:PartyLegalEntity>
		</cac:Party>
	</cac:DespatchSupplierParty>
	<cac:DeliveryCustomerParty>
		<cbc:CustomerAssignedAccountID schemeID="{{ doc.destinatario.tipoDoc }}">{{ doc.destinatario.numDoc }}</cbc:CustomerAssignedAccountID>
		<cac:Party>
			<cac:PartyLegalEntity>
				<cbc:RegistrationName><![CDATA[{{ doc.destinatario.rznSocial|raw }}]]></cbc:RegistrationName>
			</cac:PartyLegalEntity>
		</cac:Party>
	</cac:DeliveryCustomerParty>
    {% if doc.tercero -%}
	<cac:SellerSupplierParty>
		<cbc:CustomerAssignedAccountID schemeID="{{ doc.tercero.tipoDoc }}">{{ doc.tercero.numDoc }}</cbc:CustomerAssignedAccountID>
		<cac:Party>
			<cac:PartyLegalEntity>
				<cbc:RegistrationName><![CDATA[{{ doc.tercero.rznSocial|raw }}]]></cbc:RegistrationName>
			</cac:PartyLegalEntity>
		</cac:Party>
	</cac:SellerSupplierParty>
    {% endif -%}
    {% set envio = doc.envio -%}
	<cac:Shipment>
		<cbc:ID>1</cbc:ID>
		<cbc:HandlingCode>{{ envio.codTraslado }}</cbc:HandlingCode>
        {% if envio.desTraslado -%}
		<cbc:Information>{{ envio.desTraslado }}</cbc:Information>
		{% endif -%}
		<cbc:GrossWeightMeasure unitCode="{{ envio.undPesoTotal }}">{{ envio.pesoTotal|n_format(3) }}</cbc:GrossWeightMeasure>
        {% if envio.numBultos -%}
		<cbc:TotalTransportHandlingUnitQuantity>{{ envio.numBultos }}</cbc:TotalTransportHandlingUnitQuantity>
		{% endif -%}
		<cbc:SplitConsignmentIndicator>{{ envio.indTransbordo ? "true" : "false" }}</cbc:SplitConsignmentIndicator>
		<cac:ShipmentStage>
			<cbc:TransportModeCode>{{ envio.modTraslado }}</cbc:TransportModeCode>
			<cac:TransitPeriod>
				<cbc:StartDate>{{ envio.fecTraslado|date('Y-m-d') }}</cbc:StartDate>
			</cac:TransitPeriod>
            {% if envio.transportista -%}
			<cac:CarrierParty>
				<cac:PartyIdentification>
					<cbc:ID schemeID="{{ envio.transportista.tipoDoc }}">{{ envio.transportista.numDoc }}</cbc:ID>
				</cac:PartyIdentification>
				<cac:PartyName>
					<cbc:Name><![CDATA[{{ envio.transportista.rznSocial|raw }}]]></cbc:Name>
				</cac:PartyName>
			</cac:CarrierParty>
			<cac:TransportMeans>
				<cac:RoadTransport>
					<cbc:LicensePlateID>{{ envio.transportista.placa }}</cbc:LicensePlateID>
				</cac:RoadTransport>
			</cac:TransportMeans>
			<cac:DriverPerson>
				<cbc:ID schemeID="{{ envio.transportista.choferTipoDoc }}">{{ envio.transportista.choferDoc }}</cbc:ID>
			</cac:DriverPerson>
            {% endif -%}
		</cac:ShipmentStage>
		<cac:Delivery>
			<cac:DeliveryAddress>
				<cbc:ID>{{ envio.llegada.ubigueo }}</cbc:ID>
				<cbc:StreetName>{{ envio.llegada.direccion }}</cbc:StreetName>
			</cac:DeliveryAddress>
		</cac:Delivery>
        {% if envio.numContenedor -%}
		<cac:TransportHandlingUnit>
			<cbc:ID>{{ envio.numContenedor }}</cbc:ID>
		</cac:TransportHandlingUnit>
        {% endif -%}
		<cac:OriginAddress>
			<cbc:ID>{{ envio.partida.ubigueo }}</cbc:ID>
			<cbc:StreetName>{{ envio.partida.direccion }}</cbc:StreetName>
		</cac:OriginAddress>
        {% if envio.codPuerto -%}
		<cac:FirstArrivalPortLocation>
			<cbc:ID>{{ envio.codPuerto }}</cbc:ID>
		</cac:FirstArrivalPortLocation>
        {% endif -%}
	</cac:Shipment>
    {% for det in doc.details -%}
	<cac:DespatchLine>
		<cbc:ID>{{ loop.index }}</cbc:ID>
		<cbc:DeliveredQuantity unitCode="{{ det.unidad }}">{{ det.cantidad }}</cbc:DeliveredQuantity>
		<cac:OrderLineReference>
			<cbc:LineID>{{ loop.index }}</cbc:LineID>
		</cac:OrderLineReference>
		<cac:Item>
			<cbc:Name><![CDATA[{{ det.descripcion|raw }}]]></cbc:Name>
			<cac:SellersItemIdentification>
				<cbc:ID>{{ det.codigo }}</cbc:ID>
			</cac:SellersItemIdentification>
			{% if det.codProdSunat -%}
				<cac:CommodityClassification>
					<cbc:ItemClassificationCode listID="UNSPSC" listAgencyName="GS1 US" listName="Item Classification">{{ det.codProdSunat }}</cbc:ItemClassificationCode>
				</cac:CommodityClassification>
			{% endif -%}
		</cac:Item>
	</cac:DespatchLine>
    {% endfor -%}
</DespatchAdvice>