<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$config['primary_menu'] = array(
    'Rooms' => array(
        'Amenities' => 'admin/rooms/amenities',
        'Room Type' => 'admin/rooms/room-type',
        'Sort Room Types' => 'admin/rooms/sort-room-types',
        'Bed Type' => 'admin/rooms/bed-type',
        'Room' => 'admin/rooms/list-room',
        'Sort Rooms' => 'admin/rooms/sort-rooms',
        'Status Color' => 'admin/rooms/status-color'
    ),
    'Rates' => array(
        'Rate Type' => 'admin/rates/rate-type',
        'Season' => 'admin/rates/season',
        'Room Rates' => 'admin/rates/room-rates',
        'Tax' => 'admin/rates/tax',
        'Sort Rates' => 'admin/rates/sort-rates'
    ),
    'HouseKeeping' => array(
        'HouseKeeper' => 'admin/housekeeping/list-housekeeping',
        'Unit' => 'admin/housekeeping/list-unit',
        'Status' => 'admin/housekeeping/list-status',
        'Remarks' => 'admin/housekeeping/list-remarkes'
    ),
    'Master' => array(
        'General' => array(
            'Currency List' => 'admin/master/general/list/currency',
            'Pay Method' => 'admin/master/general/list/payment-method',
            /* 'Extra Charge' => 'admin/master/general/list/extra-charge',
              'Discounts' => 'admin/master/general/list/discounts', */
            'Identity Type' => 'admin/master/general/list/identity',
            'Transportation Mode' => 'admin/master/general/list/transportation',
            'Pay Out' => 'admin/master/general/list/pay-out',
            'Reservation Type' => 'admin/master/general/list/reservation-type',
            'Vip Status' => 'admin/master/general/list/vip-status',
            'Meal Plan' => 'admin/master/general/list/meal-plan'
        ),
        /* 'Email Templates' => array(
          'Template Category' => 'admin/master/email-templates/list/template-category',
          'Template' => 'admin/master/email-templates/list/template'
          ), */
        'Guest Preferences' => array(
            'Preference Type' => 'admin/master/guest-preferences/list/preference-type',
            'Preferences' => 'admin/master/guest-preferences/list/preference'
        ),
        'Reasons' => array(
            'Reason' => 'admin/master/reasons/list/reason',
            'Black List Reason' => 'admin/master/reasons/list/reason-blacklist'
        ),
        /* 'Marketing' => array(
          'Market Code' => 'admin/master/marketing/list/market-code',
          'Business Source' => 'admin/master/marketing/list/business-source',
          'Email Marketing & Scheduling' => 'admin/master/marketing/list/email-marketing-scheduling',
          'Reviews' => 'admin/master/marketing/list/reviews'
          ), */
        'Others' => array(
            'Holidays' => 'admin/master/others/list/holiday',
        )
    ),
    'Setting' => array(
        'Property' => array(
            'Hotel Information' => 'admin/setting/property/list/hotel-information',
            'Email Accounts' => 'admin/setting/property/list/email-account',
            /* 'Tax/Account Configuration' => 'admin/setting/property/tax-accounts-configuration', */
        ),
        /* 'General' => array(
          'Display Settings' => 'admin/setting/general/display-settings',
          'Print And Email Settings' => 'admin/setting/general/print-and-email-settings',
          'Check In and Check Out time settings' => 'admin/setting/general/check-in-and-check-out-time-settings',
          'Pagination Settings' => 'admin/setting/general/pagination-settings',
          'Document Numbering' => 'admin/setting/general/document-numbering',
          ), */
        /* 'Formula' => 'admin/setting/formula', */
        'Notice' => 'admin/setting/notice/',
        /* 'Others' => array(
          'Payment Gateway Settings' => 'admin/setting/others/payment-gateway-settings',
          'Guest Portal Settings' => 'admin/setting/others/guest-portal-settings',
          ), */
    ),
//    'Web' => 'admin/web',
);