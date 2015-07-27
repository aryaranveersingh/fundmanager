<?php


$data = explode(",","NetStorage,EDNS::Enhanced DNS,ISO Compliance Management,Kona Site Defender,Kona Site Defender - Legacy,Kona Site Defender: DDoS Fee Protection - Capped Burst Fee,Kona Site Defender::DDoS Fee Protection - Capped Burst Fee,PCI Compliance Management,Akamai University Customer Training - Classroom,Akamai University Customer Training - On Site,Enterprise and Technical Advisory Services::Enterprise and Technical Advisory Services,Kona Site Defender::Managed Kona Site Defender Service,Premium Service and Support 2.0::Premium Service and Support 2.0,Professional Services - Enterprise,Standard Customer Care Support,Support - Premium Support Package,Support::Support Advocacy,Aqua Ion Mobile,Aqua Ion Mobile::Adaptive Image Compression,Aqua Ion SPM Secure,Beta Channel - Ion Premier Direct,Client Access Control,Cloud Monitor,Content Targeting,Dynamic Site Accelerator - Secure Premier with HD,Global Traffic Management - Premier,Image Management::Beta,Ion Premier,Ion Premier:: Adaptive Image Compression,Ion Premier::HTTPS Option,Ion Premier::SSL Network Access - SAN,Ion Premier::SSL Network Access - Single Hostname,Ion::Fast DNS,Legacy Shopper Prioritization Application,SSL Network Access - SAN for Site Accelerator,SSL Network Access - Single Hostname for Site Accelerator,Shopper Prioritization,Site Accelerator: Real User Monitoring,Site Accelerator::SPDY,Site Analyzer.");

 // ksort($this -> resultObj  -> aloha_sales -> solutions -> case_study -> data,SORT_FLAG_CASE);
print_r($data);
sort($data,SORT_FLAG_CASE);
print_r($data);