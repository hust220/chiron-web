#!/usr/bin/perl
#

use HTML::HTMLDoc;


my $htmldoc = new HTML::HTMLDoc();

local $/ = undef;
open FH, "terms.html";
binmode FH;
$terms = <FH>;
close FH;
$htmldoc->set_html_content($terms);
#$htmldoc->set_input_file("terms.pdf");
my $pdf = $htmldoc->generate_pdf();

print $pdf->to_string();
$pdf->to_file('terms.pdf');
