<!-- logging tab -->
<div class="tab-pane fade" id="ntplogging">
  <h4 class="mt-3 mb-3"><?php echo _("NTP status") ;?></h4>
  <p>
    <?php echo _("Current <code>ntpq peer</code> status is displayed below. An asterisk (<code>*</code>) indicates the preferred server."); ?>
  </p>
  <div class="row">
    <div class="mb-3 col-md-8 mt-2">
      <textarea class="logoutput text-secondary"><?php echo htmlspecialchars($__template_data['serviceLog'], ENT_QUOTES); ?></textarea>
    </div>
  </div>
</div><!-- /.tab-pane -->


