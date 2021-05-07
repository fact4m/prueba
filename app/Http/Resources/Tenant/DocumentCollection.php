<?php

namespace App\Http\Resources\Tenant;

use App\Models\Tenant\Configuration;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DocumentCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function toArray($request)
    {
        return $this->collection->transform(function($row, $key) {

            $btn_voided = false;
            $btn_ticket = false;
            $btn_note = false;
            $btn_resend = false;
            $has_xml_voided = false;
            $has_cdr_voided = false;
            $download_xml_voided = null;
            $download_cdr_voided = null;
            $voided = null;
            $affected_document = null;

            if ($row->state_type_id === '05') {
                $btn_voided = true;
                $btn_note = true;
            }
            if ($row->state_type_id === '13') {
                $has_xml_voided = true;
                $btn_ticket = true;
                $voided = $row->voided;
                if ($row->group_id === '01') {
                    $download_xml_voided = route('tenant.voided.download', ['type' => 'xml', 'voided' => $voided->id]);
                } else {
                    $download_xml_voided = route('tenant.summaries.download', ['type' => 'xml', 'summary' => $voided->id]);
                }
            }
            if ($row->state_type_id === '11') {
                $has_xml_voided = true;
                $has_cdr_voided = true;
                $voided = $row->voided;
                if ($row->group_id === '01') {
                    $download_xml_voided = route('tenant.voided.download', ['type' => 'xml', 'voided' => $voided->id]);
                    $download_cdr_voided = route('tenant.voided.download', ['type' => 'cdr', 'voided' => $voided->id]);
                } else {
                    $download_xml_voided = route('tenant.summaries.download', ['type' => 'xml', 'summary' => $voided->id]);
                    $download_cdr_voided = route('tenant.summaries.download', ['type' => 'cdr', 'summary' => $voided->id]);
                }
            }

            if (in_array($row->document_type_code, ['07', '08'])) {
                $btn_note = false;
                $affected_document = $row->note->affected_document_series.'-'.$row->note->affected_document_number;
            }

            $total_free = 0;
            if (in_array($row->document_type_code, ['01', '03'])) {
                $total_free = $row->invoice->total_free;
            }

            if ($row->group_id === '01') {
                if ($row->state_type_id === '01') {
                    $btn_resend = true;
                }
            }

            $configuration = Configuration::first();
            $btn_delete_demo = false;
            if($row->soap_type_id === '01') {
                if(!$configuration) {
                    $btn_delete_demo = false;
                } else {
                    $btn_delete_demo = (bool) $configuration->delete_document_demo;
                }
            }

            return [
                'id' => $row->id,
                'group_id' => $row->group_id,
                'soap_type_id' => $row->soap_type_id,
                'date_of_issue' => $row->date_of_issue->format('Y-m-d'),
                'number' => $row->number_full,
                'customer_name' => $row->customer->name,
                'customer_number' => $row->customer->identity_document_type->description.' '.$row->customer->number,
                'total_exonerated' => $row->total_exonerated,
                'total_taxed' => $row->total_taxed,
                'total_free' => $total_free,
                'total_igv' => $row->total_igv,
                'total' => $row->total,
                'state_type_id' => $row->state_type_id,
                'state_type_description' => $row->state_type->description,
                'document_type_description' => $row->document_type_description,
                'has_xml' => $row->has_xml,
                'has_pdf' => $row->has_pdf,
                'has_cdr' => $row->has_cdr,
                'download_xml' => $row->download_xml,
                'download_pdf' => $row->download_pdf,
                'download_cdr' => $row->download_cdr,
                'btn_voided' => $btn_voided,
                'btn_ticket' => $btn_ticket,
                'btn_note' => $btn_note,
                'btn_resend' => $btn_resend,
                'btn_delete_demo' => $btn_delete_demo,
                'voided' => $voided,
                'affected_document' => $affected_document,
                'has_xml_voided' => $has_xml_voided,
                'has_cdr_voided' => $has_cdr_voided,
                'download_xml_voided' => $download_xml_voided,
                'download_cdr_voided' => $download_cdr_voided,
                'created_at' => $row->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $row->updated_at->format('Y-m-d H:i:s'),
            ];
        });
    }
}