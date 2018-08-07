use PDF::Create;

my $pdf = new PDF::Create('filename'=>'terms.pdf',
									'Author'=>'Chiron',
									'Title'=>'Results',
									'CreationDate'=>[ localtime ], );

my $a4 = $pdf->new_page('MediaBox'=>$pdf->get_page_size('A4'));

my $page = $a4->new_page;

my $f1 = $pdf->font('BaseFont'=>'Helvetica');

my $toc = $pdf->new_outline('Title'=>'Results', 'Destination'=>$page);

$page->stringc($f2, 40, 306, 426, "PDF::Create");
$page->stringc($f1, 20, 306, 396, "version $PDF::Create::VERSION");
$page->stringc($f1, 20, 306, 300, 'Pradeep Kota <pkota@email.unc.edu>');

my $page2 = $a4->new_page;

$page2->line(0, 0, 612, 792);
$page2->line(0, 792, 612, 0);

$toc->new_outline('Title' => 'Second Page', 'Destination' => $page2);

$pdf->close;

