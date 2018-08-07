<?php

require('config/config.inc.php');

?>
<?php
	if (empty( $_SESSION['username']) ){
		header("Location: login.php");
	}
?>
<html>
<?php 
	include("txt/head.txt");
?>
	<div id="content">
		<div id="nav">
		<?php 
			include("txt/menu.php");
		?>
		</div>
		<div id="main_content">
			<div class="indexTitle">
				The Chiron server - Frequently asked questions
			</div>
			<h2>Scientific</h2>

			<div id="div_scientific" style="padding-left:25px;">
			
				<h3><strong><a href="javascript:slide_sq1.slideIt();slide_sq1.hideRest();">What is Chiron?</a></strong></h3>
				<div id="div_sq1" style="display:none"><p>Named after the Thessalian god of healing, Chiron is a server that rapidly minimizes steric clashes in proteins using short discrete molecular dynamics(DMD) simulations.</p></div>
				
				<h3><strong><a href="javascript:slide_sq2.slideIt();slide_sq2.hideRest();">What is a steric clash?</a></strong></h3>
				<div id="div_sq2" style="display:none">
					<p>Any atomic overlap resulting in Van der Waal&#39;s repulsion energy greater than 0.3 kcal/mol (0.5 k<sub>B</sub>T) except in the following cases:<ol><li>When the atoms are bonded</li><li>When the atoms form a hydrogen bond (The heavy atoms involved in the hydrogen bond, we assign the Van der Waal&#39;s radius of hydrogen to be zero)</li><li>When the atoms involved are backbone atoms and have separation of 2 residues.</li></ol>
 The Van der Waal&#39;s repulsion energy is calculated using CHARMM non-bonded  parameters, which are identical to CNS parameters except for carboxyl oxygens.
</p>
				</div>
				
				<h3><strong><a href="javascript:slide_sq3.slideIt();slide_sq3.hideRest();">Why care about clashes?</a></strong></h3>
				<div id="div_sq3" style="display:none">
					<p>Steric clashes arise when there is unfavorable overlap of the electron clouds of two atoms. Proteins pack tight enough to avoid these clashes. Even though only limited amount of clashes are seen in high resolution crystal structures of proteins (probably to facilitate formation of hydrogen bonds and/or electrostatic interactions), many protein structures feature large number of clashes, either because of lack of resolution, or as side-effects of homology models. One would expect that while generating homology models, the probability of side-chain atoms clashing among themselves and with the backbone atoms increases upon changing side chain identities of the protein. In fact, we observe that the mean normalized clash energy of high-resolution crystal structures (2.5-3.5 &Aring;) is significantly higher than that for structures with resolution better than 2.5 &Aring;. In these cases, the clashes are not the property of the protein but rather an artifact of model building. To arrive at a more physical model in such cases, one would like to first identify these clashes and then remove them with minimum perturbation of the backbone.</p>
				</div>
				
				<h3><strong><a href="javascript:slide_sq4.slideIt();slide_sq4.hideRest();">How does Chiron remove clashes?</a></strong></h3>
				<div id="div_sq4" style="display:none">
					<p>MD or DMD performed under physiological conditions with the structures having high clash energy would result in the protein rapidly unfolding. However, Chiron utilizes a high heat exchange rate of the solute (protein) with the bath in DMD simulations; thus rapidly quenching high velocities arising due to clashes. Using our simulation conditions and the inherent sampling power of DMD, Chiron employs an iterative protocol aimed at minimizing the given protein until it attains an &#39;acceptable clash score&#39;. Chiron rapidly minimizes clashes while at the same time causing minimal perturbation of the protein backbone. The resulting protein structure has normalized clash score that is comparable to high-resolution protein structures (&lt;2.5 &Aring;).</p>
				</div>
				
				<h3><strong><a href="javascript:slide_sq5.slideIt();slide_sq5.hideRest();">How did you arrive at the &#39;acceptable clash score&#39;?</a></strong></h3>
				<div id="div_sq5" style="display:none">
					<p>We built a distribution of normalized clash-score using nearly 4300 single chain crystal structures at least 25 residues long and having resolution equal to or better than 2.5 &Aring;. A structure with normalized clash score less than one standard deviation away from the mean of the above distribution is said to have an acceptable clash score.</p>
				</div>
			
			</div>
			
			<h2>Technical</h2>
			<div id="div_technical" style="padding-left:25px;">
				<h3><strong><a href="javascript:slide_tq1.slideIt();slide_tq1.hideRest();">How do I submit a job?</a></strong></h3>
				<div id="div_tq1" style="display:none"><p>For every minimization run, the server requires a protein structure, which can be provided either by specifying a PDB identifier or by a direct file upload. The submitted tasks are queued and processed when there are available computational resources. The recently submitted tasks (since last login) are listed under the "Home/Overview" menu.</p></div>
			
				<h3><strong><a href="javascript:slide_tq2.slideIt();slide_tq2.hideRest();">Can my PDB contain small molecules?</a></strong></h3>
				<div id="div_tq2" style="display:none"><p>Chiron currently recognizes small molecules in a given PDB and maintains them static during the minimization process. During job submission, you may select the small molecules you want to consider for minimization by selecting the checkboxes next to the desired molecule names. If the PDB contains small molecules, both the clashes of the protein atoms within themselves and with the small molecules will be minimized to acceptable levels. Currently, some atom types are not supported by Chiron. Furthermore, bonds between atoms of the protein and the small molecule will not be recognized. Do not submit jobs that do not conform to the above requirements since they may fail.</p></div>
			
				<h3><strong><a href="javascript:slide_tq3.slideIt();slide_tq3.hideRest();">How do I view results?</a></strong></h3>
				<div id="div_tq3" style="display:none"><p>Finished jobs are listed under &#39;User Activity&#39; page. Clicking the &#39;eye&#39; icon under action column for a particular job will take you to the results page ofthat job. In the result page, you can<ol><li>Download the minimized structure in the PDB format</li><li>View/Download the clash reports of the initial and final structures that lists all the atom-pairs that clash, their distance of separation, their accepted distance of separation (at which the repulsive energy will be less than 0.3 kcal/mol) and the raw clash-energy.</li><li>Download the PyMOL script (see &#39;What do I do with the PyMOL script (.py file)?&#39;).</li><li>View the minimization summary (see &#39;What is displayed under Minimization Summary&#39;?).</li></ol></p></div>
			
				<h3><strong><a href="javascript:slide_tq4.slideIt();slide_tq4.hideRest();">What do I do with the PyMOL script (.py file)?</a></strong></h3>
				<div id="div_tq4" style="display:none"><p>To enable visualization of the clashes before and after minmization, Chiron also provides a python script that can be used in conjunction with pymol (http://www.pymol.org/). Just download the .py file and open it with pymol. Alternatively, if pymol is open, type in the pymol command line &#39;run &lt;.py file with path&lt; &#39;  and press return</p><p><strong>Note : The python script will refresh pymol to start a new session, so save existing work before running the script. Before running the script, make sure the current directory is writeable.</strong></p><p>Upon successfully running the script, two objects will be created. The first object is the protein structure after minimization, represented with lines and cartoon. The second object contains all the clashes as cylinders. The clashes are color-coded (rainbow spectrum) cylinders of different base-radii. Cylinders with the smallest base radii and colored violet denote the clashes with the lowest repulsion energy in the structure, while cylinders with the largest base radii and colored red correspond to the clashes with highest repulsion energy.</p></div>
			
				<h3><strong><a href="javascript:slide_tq5.slideIt();slide_tq5.hideRest();">What is displayed under &#39;Minimization Summary&#39;?</a></strong></h3>
				<div id="div_tq5" style="display:none"><p>To compare the clash-score of the input and minimized structures to our benchmark set of 4300 high-resolution structures, we plot the distribution of the normalized clash-score of our benchmark set and indicate the intial and final clash-score in the plot with respect to the distribution.</p></div>
			
				<h3><strong><a href="javascript:slide_tq6.slideIt();slide_tq6.hideRest();">Why did my job fail?</a></strong></h3>
				<div id="div_tq6" style="display:none"><p>Please shoot an email to the site admin and we will fix individual issues. There are many possible issues with input pdb files, some of which we list here and some under the question Why does the server say <strong>&#34;Error loading pdb file&#34;</strong>?, but the list is not exhaustive.<ol><li>The PDB has non-protein atoms, like DNA or RNA. The server does not read in HETATM, so that is not a problem, but the server does not recognize ATOM records that contain non-protein coordinates.</li><li>Multi-Chain PDBs are acceptable, however, the chains should be separated by either TER card or have different chain-IDs to differentiate chains. Continuous residue numbering combined with same chain ID and lack of TER card between two chains might cause jobs to fail.</li><li>If there is a chain break (missing coordinates for residues of a loop, for example), the server will still work. However, such chain breaks need to be indicated in one of these ways: retaining original residue numbering (then the server will detect missing residue numbers, thus accounting for chain break) or by putting a TER card between the residues lining the break, or by using different chain IDs for different parts of the broken chain.</li><li>Some PDB files have a continuous chain but discontinuous residue ids. Such a case would be wrongly identified as a chain break, causing DMD to fail.</li><li>If the HETATM section of the input pdb contains atom types that have not yet been parametrized in DMD or if it contains atoms that are bonded to the protein, your job is likely to fail. Also, bonds between small molecules and protein atoms are not recognized and hence reported as clashes.</li></ol></div>
			
				<h3><strong><a href="javascript:slide_tq7.slideIt();slide_tq7.hideRest();">Why does the server say &#34;Error loading pdb file&#34;?</a></strong></h3>
				<div id="div_tq7" style="display:none"><p>This error refers to a case where our program encounters an error reading the PDB file. Following are some of the possible reasons.<ol><li>You might have provided a PDB ID that does not exist.</li><li>Your PDB file contains unnatural amino acids. Our current implementation only supports the 20 natural amino acid types: ALA, CYS, ASP, GLU, PHE, GLY, HIS, ILE, LYS, LEU, MET, ASN, PRO, GLN, ARG, SER, THR, VAL, TRP and TYR. Existence of residues other than the listed types in the ATOM records can cause the error.</li><li>Backbone heavy atoms are missing. The program requires coordinates of all backbone N, C and CA atoms for each residue, and will reconstruct other missing atoms from the input pdb file.</li></ol></p></div>
			</div>
		</div>
		<script type="text/javascript">
			var slide_sq1 = new animatedDiv('slide_sq1','div_sq1',500,null,false,null);
			var slide_sq2 = new animatedDiv('slide_sq2','div_sq2',500,null,false,null);
			var slide_sq3 = new animatedDiv('slide_sq3','div_sq3',500,null,false,null);
			var slide_sq4 = new animatedDiv('slide_sq4','div_sq4',500,null,false,null);
			var slide_sq5 = new animatedDiv('slide_sq5','div_sq5',500,null,false,null);

			var slide_tq1 = new animatedDiv('slide_tq1','div_tq1',500,null,false,null);
			var slide_tq2 = new animatedDiv('slide_tq2','div_tq2',500,null,false,null);
			var slide_tq3 = new animatedDiv('slide_tq3','div_tq3',500,null,false,null);
			var slide_tq4 = new animatedDiv('slide_tq4','div_tq4',500,null,false,null);
			var slide_tq5 = new animatedDiv('slide_tq5','div_tq5',500,null,false,null);
			var slide_tq6 = new animatedDiv('slide_tq6','div_tq6',500,null,false,null);
			var slide_tq7 = new animatedDiv('slide_tq7','div_tq7',500,null,false,null);
		</script>
		<div id="rightbar">
			<div id="ataglance">
				your infomration at a glance.
			</div>
		</div>
	</div>
</body>
</html>
		<div id="rightbar">
			<div id="ataglance">
				your infomration at a glance.
			</div>
		</div>
	</div>
</body>
</html>
