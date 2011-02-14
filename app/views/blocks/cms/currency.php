						<div class="message">
							<p>
								<?php echo \Sys\ZeroG::__('Currency format:'); ?><br/>
								- <?php echo \Sys\ZeroG::L()->getCurrency('USD')->format(1); ?><br/>
								- <?php echo \Sys\ZeroG::L()->getCurrency('USD')->format(2987.3982); ?>
							</p>
						</div>