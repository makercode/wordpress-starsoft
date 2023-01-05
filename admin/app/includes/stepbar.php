<?php 
	
	$loggedGuard = new LoggedGuard;
	$isLogged = $loggedGuard->isLogged();

	$validatedGuard = new ValidatedGuard;
	$isValidated = $validatedGuard->isValidated();

	$choosedGuard = new ChoosedGuard;
	$isChoosed = $choosedGuard->isChoosed();

 ?>
<div class="stepper">
	<div class="stepbar">
		<?php if($isLogged=="0" && $isValidated=="0" && $isChoosed=="0") { ?>
			<div href="#" class="step step-login processing">
				<span class="step-position">
					1
				</span>
				Identificando...
			</div>
			<div href="#" class="step step-validation">
				<span class="step-position">
					!
				</span>
				No Validado
			</div>
			<div href="#" class="step step-validation">
				<span class="step-position">
					!
				</span>
				No Seleccionado
			</div>
			<div href="#" class="step step-synchronization">
				<span class="step-position">
					!
				</span>
				No Sincronizado
			</div>
		<?php } ?>
		<?php if($isLogged=="1" && $isValidated=="0" && $isChoosed=="0") { ?>
			<div href="#" class="step step-login active">
				<span class="step-position">
				✓
				</span>
				Identificado
			</div>
			<div href="#" class="step step-validation processing">
				<span class="step-position">
				2
				</span>
				Validando...
			</div>
			<div href="#" class="step step-validation">
				<span class="step-position">
					!
				</span>
				No Seleccionado
			</div>
			<div href="#" class="step step-synchronization">
				<span class="step-position">
				!
				</span>
				No Sincronizado
			</div>
		<?php } ?>
		<?php if($isLogged=="1" && $isValidated=="1" && $isChoosed=="0") { ?>
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
			<div href="#" class="step step-validation processing">
				<span class="step-position">
				3
				</span>
				Seleccionando...
			</div>
			<div href="#" class="step step-synchronization">
				<span class="step-position">
				!
				</span>
				No Sincronizado
			</div>
		<?php } ?>
		<?php if($isLogged=="1" && $isValidated=="1" && $isChoosed=="1") { ?>
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
			<div href="#" class="step step-choosed active">
				<span class="step-position">
				✓
				</span>
				Seleccionado
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