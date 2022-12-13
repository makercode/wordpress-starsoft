<?php 
	
	$loggedGuard = new LoggedGuard;
	$is_logged = $loggedGuard->isLogged();

	$validatedGuard = new ValidatedGuard;
	$is_validated = $validatedGuard->isValidated();

 ?>
<div class="stepper">
	<div class="stepbar">
		<?php if($is_logged=="0" && $is_validated=="0") { ?>
			<div href="#" class="step step-login processing">
				<span class="step-position">
					!
				</span>
				Identificando...
			</div>
			<div href="#" class="step step-validation">
				<span class="step-position">
					!
				</span>
				No Validado
			</div>
			<div href="#" class="step step-synchronization">
				<span class="step-position">
					!
				</span>
				No Sincronizado
			</div>
		<?php } ?>
		<?php if($is_logged=="1" && $is_validated=="0") { ?>
			<div href="#" class="step step-login active">
				<span class="step-position">
				✓
				</span>
				Identificado
			</div>
			<div href="#" class="step step-validation processing">
				<span class="step-position">
				!
				</span>
				Validando...
			</div>
			<div href="#" class="step step-synchronization">
				<span class="step-position">
				!
				</span>
				No Sincronizado
			</div>
		<?php } ?>
		<?php if($is_logged=="1" && $is_validated=="1") { ?>
			<div href="#" class="step step-login active">
				<span class="step-position">
				✓
				</span>
				Identificado
			</div>
			<div href="#" class="step step-validation active">
				<span class="step-position">
				✓
				</span>
				Validado
			</div>
			<div href="#" class="step step-synchronization active">
				<span class="step-position">
				✓
				</span>
				Sincronizado
			</div>
		<?php } ?>
	</div>
</div>