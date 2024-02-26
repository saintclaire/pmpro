<?php
/*
Plugin Name: Paid Memberships Pro - Export User PDF Addon 
Plugin URI: https://www.paidmembershipspro.com/wp/pmpro-customizations/
Description: Export Ewn Applicant in PDF
Version: .1
Author: Chrys Avell De Saint Claire
Author URI: https://www.paidmembershipspro.com
*/
 
//Now start placing your customization code below this line
 //include('../paid-memberships-pro/includes/functions.php');
 
    
 


// Register the export_pdf capability
function ewn_register_export_pdf_capability() {
    // Add the export_pdf capability
    $role = get_role('administrator');
    $role->add_cap('export_pdf');
}
register_activation_hook(__FILE__, 'register_export_pdf_capability');
// Add a custom action to the user row actions
function ewn_add_user_row_action($actions, $user_object) {
    // Check if the current user has the capability to export PDFs
    if (current_user_can('export_pdf')) {
        // Add the Export PDF action
        $actions['export_pdf'] = '<a href="' . esc_url(wp_nonce_url(admin_url('admin-ajax.php?action=export_user_pdf&user_id=' . $user_object->ID), 'export_user_pdf')) . '">Export PDF</a>';
    }
    
    return $actions;
}

add_filter('user_row_actions', 'ewn_add_user_row_action', 10, 2);




// Ajax handler for exporting user data as PDF
function ewn_export_user_pdf() {
    global $wpdb;
    // Check if the current user has the capability to export PDFs
    if (!current_user_can('export_pdf')) {
        wp_die();
    }
    require('fpdf184/fpdf.php');

 
    
    // Get the user ID from the Ajax request
    $user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
    // Retrieve user data
    $user = get_user_by('ID', $user_id);
    $membership_level = pmpro_getMembershipLevelForUser($user_id);
    $user_info = get_userdata( $user_id );
    $primary_address= get_user_meta( $user_id,'primary_address', true)?: 'N/A';
	$first_name=get_user_meta( $user_id,'first_name', true)?: 'N/A';
	$last_name=get_user_meta($user_id,'last_name',true)?: 'N/A'; 
	$date_of_birth=get_user_meta($user_id,'date_of_birth',true)?: 'N/A';
	$mobile=get_user_meta($user_id,'mobile',true)?: 'N/A';
	$telephone=get_user_meta($user_id,'telephone',true)?: 'N/A';
	$highest_educational_qualification=get_user_meta($user_id,'highest_educational_qualification',true)?: 'N/A';
	$do_you_own_a_business_=get_user_meta($user_id,'do_you_own_a_business_',true)?: 'N/A';
	$occupation_job_title=get_user_meta($user_id,'occupation_job_title',true)?: 'N/A';
	$duration_in_your_current_position=get_user_meta($user_id,'duration_in_your_current_position',true)?: 'N/A';
	$previous_position=get_user_meta($user_id,'previous_position',true)?: 'N/A';
	$number_of_years=get_user_meta($user_id,'number_of_years',true)?: 'N/A';
	$company_name=get_user_meta($user_id,'company_name',true)?: 'N/A';
	$company_address =get_user_meta($user_id,'company_address',true)?: 'N/A';
    $company_telephone =get_user_meta($user_id,'company_telephone',true)?: 'N/A'; 
    $company_website =get_user_meta($user_id,'company_website',true)?: 'N/A'; 
    $your_work_email_address =get_user_meta($user_id,'your_work_email_address',true)?: 'N/A'; 
    $business_name =get_user_meta($user_id,'business_name',true)?: 'N/A';
    $type_of_business =get_user_meta($user_id,'type_of_business',true)?: 'N/A';
    $capital_invested =get_user_meta($user_id,'capital_invested',true)?: 'N/A'; 
    $year_of_establishment =get_user_meta($user_id,'year_of_establishment',true)?: 'N/A'; 
    $annual_turnover =get_user_meta($user_id,'annual_turnover',true)?: 'N/A'; 
    $number_of_staff_employed =get_user_meta($user_id,'number_of_staff_employed',true)?: 'N/A'; 
    $name_of_company =get_user_meta($user_id,'name_of_company',true)?: 'N/A'; 
    $_number_of_years_in_executive_position =get_user_meta($user_id,'_number_of_years_in_executive_position',true)?: 'N/A';
    $business_address =get_user_meta($user_id,'business_address',true)?: 'N/A';
    $business_telephone =get_user_meta($user_id,'business_telephone',true)?: 'N/A';
    $business_website =get_user_meta($user_id,'business_website',true)?: 'N/A';
    $business_email_address =get_user_meta($user_id,'business_email_address',true)?: 'N/A'; 
    $industry = get_user_meta($user_id, 'industry', true)?: 'N/A';
    $how_did_you_hear_of_ewn_ =get_user_meta($user_id,'how_did_you_hear_of_ewn_',true)?: 'N/A';
    $name_membership_number =get_user_meta($user_id,'name_membership_number',true)?: 'N/A';
    $specify =get_user_meta($user_id,'specify',true)?: 'N/A'; 
	$skills =get_user_meta($user_id,'list_your_skills_areas_of_competence',true)?: 'N/A'; 
	$briefly =get_user_meta($user_id,'briefly_tell_us_why_you_would_like_to_be_a_member_of_ewn_and_what_your_expectations_are_for_joining_this_network',true)?: 'N/A'; 
    $country=get_user_meta($user_id,'country',true)?: 'N/A'; 
    // Create a new PDF instance

    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Times','B',20);
          
    // Move to the right
    $pdf->Cell(80);
      
    // Set the title of pages.
    $pdf->Cell(30, 20, 'EWN Application form', 0, 2, 'C');
      
    // Break line with given space
$pdf->Ln(5);
$pdf->SetFont('Times','',12);
   $pdf->Cell(0,10,'First Name: '.$user_info->first_name,0,1);
   $pdf->Cell(0,10,'Last Name: '.$user_info->last_name,0,1);
   $pdf->Cell(0,10,'Email Address: '.$user_info->user_email,0,1);
   $pdf->Cell(0,10,'Country: '.$country,0,1);
   $pdf->Cell(0,10,'Date of Birth: '.$date_of_birth['d'].'/'.$date_of_birth['m'].'/'.$date_of_birth['y'],0,1);
   $pdf->Cell(0,10,'Primary address: '.$primary_address,0,1);
   $pdf->Cell(0,10,'Mobile: '.$mobile,0,1);
   $pdf->Cell(0,10,'Telephone: '.$telephone,0,1);
   $pdf->Cell(0,10,'Highest educational qualification: '.$highest_educational_qualification,0,1);
//    $pdf->Cell(0,10,'Do you own a business ?: '.$do_you_own_a_business_,0,1);
if ($business_name !== 'N/A' || 
    $type_of_business !== 'N/A' || 
    $capital_invested !== 'N/A' || 
    $year_of_establishment !== 'N/A' || 
    $annual_turnover !== 'N/A' || 
    $number_of_staff_employed !== 'N/A') {
   $pdf->SetFont('Times','B',14);
   $pdf->Cell(0,10,'Business Owners/ Entrepreneurs only',0,1,'C');
   $pdf->SetFont('Times','',12);
   $pdf->Cell(0,10,'Business Name: '.$business_name,0,1,);
   $pdf->Cell(0,10,'Type of Business: '.$type_of_business,0,1,);
   $pdf->Cell(0,10,'Capital Invested: '.$capital_invested,0,1,);
   $pdf->Cell(0,10,'Year of Establishment: '.$year_of_establishment,0,1,);
   $pdf->Cell(0,10,'Annual Turnover: '.$annual_turnover,0,1,);
   $pdf->Cell(0,10,'Number of Staff Employed: '.$number_of_staff_employed,0,1,);
}
   if ($occupation_job_title !== 'N/A' || 
   $duration_in_your_current_position !== 'N/A' || 
   $previous_position !== 'N/A' || 
   $number_of_years !== 'N/A' || 
   $company_name !== 'N/A' || 
   $company_address !== 'N/A' || 
   $company_telephone !== 'N/A' || 
   $company_website !== 'N/A' || 
   $your_work_email_address !== 'N/A') {
   $pdf->SetFont('Times','B',14);
   $pdf->Cell(0,10,'Previous Work Experience',0,1,'C');
   $pdf->SetFont('Times','',12);
   $pdf->Cell(0,10,'Occupation /Job title: '.$occupation_job_title,0,1,);
   $pdf->Cell(0,10,'Duration in your current position: '.$duration_in_your_current_position,0,1,);
   $pdf->Cell(0,10,'Company name: '.$company_name,0,1,);
   $pdf->Cell(0,10,'Company address: '.$company_address,0,1,);
   $pdf->Cell(0,10,'Company Telephone: '.$company_telephone,0,1,);
   $pdf->Cell(0,10,'Company Website: '.$company_website,0,1,);
   $pdf->Cell(0,10,'Your Work Email address '.$your_work_email_address,0,1,);
   }
   if ($business_name !== 'N/A' || 
   $_number_of_years_in_executive_position !== 'N/A' || 
   $business_address !== 'N/A' || 
   $business_telephone !== 'N/A' || 
   $business_website !== 'N/A' || 
   $business_email_address !== 'N/A') {
   $pdf->SetFont('Times','B',14);
   $pdf->Cell(0,10,'Corporate Experience where applicable',0,1,'C');
   $pdf->SetFont('Times','',12);
   $pdf->Cell(0,10,'Name of Company: '.$business_name ,0,1,);
   $pdf->Cell(0,10,'Number of years in executive position: '.$_number_of_years_in_executive_position,0,1,);
   $pdf->Cell(0,10,'Business address: '.$business_address,0,1,);
   $pdf->Cell(0,10,'Business Telephone: '.$business_telephone,0,1,);
   $pdf->Cell(0,10,'Business Website: '.$business_website,0,1,);
   $pdf->Cell(0,10,'Business Email address: '.$business_email_address,0,1,);
    }
   $pdf->SetFont('Times','B',14);
   $pdf->Cell(0,10,'Miscelleanous',0,1,'C');
   $pdf->SetFont('Times','',12);
   $pdf->Cell(0,10,'Industry: '.$industry,0,1,);
   $pdf->Cell(0,10,'How did you hear of EWN? '.$how_did_you_hear_of_ewn_,0,1,);
   $pdf->Cell(0,10,'Name & membership number: '.$name_membership_number,0,1,);
   $pdf->MultiCell(0,10,'List your Skills/Areas of Competence: '.$skills,0,1,);
   $pdf->MultiCell(0,10,'Briefly tell us why you would like to be a member of EWN and what your expectations are for joining this network: '.$briefly,0,1,);
   //$pdf->Output(__DIR__ . '/membership_application.pdf', 'F');
    // Add more cells for other user data fields
    
    // Output the PDF
    $pdf->Output($first_name.'_'.$last_name.'.pdf', 'D'); // 'D' to force download
    
    // Exit the script
  
}
add_action('wp_ajax_export_user_pdf','ewn_export_user_pdf');
add_action('wp_ajax_nopriv_export_user_pdf', 'ewn_export_user_pdf');


// Enqueue custom.js file
function ewn_enqueue_custom_script() {
    // Register the script
    wp_register_script('custom-script', get_template_directory_uri() . '/custom.js', array('jquery'), '1.0', true);

    // Enqueue the script
    wp_enqueue_script('custom-script');
}
add_action('wp_enqueue_scripts', 'ewn_enqueue_custom_script');








