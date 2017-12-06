<!doctype html>
<html>
  <head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Thanks for your order!</title>
    <style>
      /* -------------------------------------
          GLOBAL RESETS
      ------------------------------------- */
      img {
        border: none;
        -ms-interpolation-mode: bicubic;
        max-width: 100%; }
      body {
        background-color: #f6f6f6;
        font-family: sans-serif;
        -webkit-font-smoothing: antialiased;
        font-size: 14px;
        line-height: 1.4;
        margin: 0;
        padding: 0; 
        -ms-text-size-adjust: 100%;
        -webkit-text-size-adjust: 100%;
        color: #292525;}
      table {
        border-collapse: separate;
        mso-table-lspace: 0pt;
        mso-table-rspace: 0pt;
        width: 100%; }
        table td {
          font-family: sans-serif;
          font-size: 14px;
          vertical-align: top; }
      /* -------------------------------------
          BODY & CONTAINER
      ------------------------------------- */
      .body {
        background-color: #f6f6f6;
        width: 100%; }
      /* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
      .container {
        display: block;
        Margin: 0 auto !important;
        /* makes it centered */
        max-width: 580px;
        padding: 10px;
        width: 580px; }
      /* This should also be a block element, so that it will fill 100% of the .container */
      .content {
        box-sizing: border-box;
        display: block;
        Margin: 0 auto;
        max-width: 580px;
        padding: 10px; }
      /* -------------------------------------
          HEADER, FOOTER, MAIN
      ------------------------------------- */
      .main {
        background: #FFFDF1;
        border-radius: 3px;
        width: 100%;
        border:1px solid #efeeee;}
      .wrapper {
        box-sizing: border-box;
        padding: 20px; }
      .footer {
        clear: both;
        padding-top: 10px;
        text-align: center;
        width: 100%; }
        .footer td,
        .footer p,
        .footer span,
        .footer a {
          color: #999999;
          font-size: 12px;
          text-align: center; }
      /* -------------------------------------
          TYPOGRAPHY
      ------------------------------------- */
      h1,
      h2,
      h3,
      h4 {
        color: #292525;
        font-family: sans-serif;
        font-weight: 400;
        line-height: 1.4;
        margin: 0;
        Margin-bottom: 30px; }
      h1 {
        font-size: 35px;
        font-weight: 300;
        text-align: center;
        text-transform: capitalize; }
      p,
      ul,
      ol {
        font-family: sans-serif;
        font-size: 14px;
        font-weight: normal;
        margin: 0;
        Margin-bottom: 15px;
      color: #292525;}
        p li,
        ul li,
        ol li {
          list-style-position: inside;
          margin-left: 5px; }
      a {
        color: #FDB398;
        text-decoration: underline; }
      /* -------------------------------------
          BUTTONS
      ------------------------------------- */
      .btn {
        box-sizing: border-box;
        width: 100%; }
        .btn > tbody > tr > td {
          padding-bottom: 15px; }
        .btn table {
          width: auto; }
        .btn table td {
          background-color: #ffffff;
          border-radius: 5px;
          text-align: center; }
        .btn a {
          background-color: #ffffff;
          border: solid 1px #FDB398;
          border-radius: 5px;
          box-sizing: border-box;
          color: #FDB398;
          cursor: pointer;
          display: inline-block;
          font-size: 14px;
          font-weight: bold;
          margin: 0;
          padding: 12px 25px;
          text-decoration: none;
          text-transform: capitalize; }
      .btn-primary table td {
        background-color: #FDB398; }
      .btn-primary a {
        background-color: #FDB398;
        border-color: #efeeee;
        color: #ffffff; }
      /* -------------------------------------
          OTHER STYLES THAT MIGHT BE USEFUL
      ------------------------------------- */
      .last {
        margin-bottom: 0; }
      .first {
        margin-top: 0; }
      .align-center {
        text-align: center; }
      .align-right {
        text-align: right; }
      .align-left {
        text-align: left; }
      .clear {
        clear: both; }
      .mt0 {
        margin-top: 0; }
      .mb0 {
        margin-bottom: 0; }
      .preheader {
        color: transparent;
        display: none;
        height: 0;
        max-height: 0;
        max-width: 0;
        opacity: 0;
        overflow: hidden;
        mso-hide: all;
        visibility: hidden;
        width: 0; }
      .powered-by a {
        text-decoration: none; }
      hr {
        border: 0;
        border-bottom: 1px solid #f6f6f6;
        Margin: 20px 0; }
      /* -------------------------------------
          RESPONSIVE AND MOBILE FRIENDLY STYLES
      ------------------------------------- */
      @media only screen and (max-width: 620px) {
        table[class=body] h1 {
          font-size: 28px !important;
          margin-bottom: 10px !important; }
        table[class=body] p,
        table[class=body] ul,
        table[class=body] ol,
        table[class=body] td,
        table[class=body] span,
        table[class=body] a {
          font-size: 16px !important; }
        table[class=body] .wrapper,
        table[class=body] .article {
          padding: 10px !important; }
        table[class=body] .content {
          padding: 0 !important; }
        table[class=body] .container {
          padding: 0 !important;
          width: 100% !important; }
        table[class=body] .main {
          border-left-width: 0 !important;
          border-radius: 0 !important;
          border-right-width: 0 !important; }
        table[class=body] .btn table {
          width: 100% !important; }
        table[class=body] .btn a {
          width: 100% !important; }
        table[class=body] .img-responsive {
          height: auto !important;
          max-width: 100% !important;
          width: auto !important; }}
      /* -------------------------------------
          PRESERVE THESE STYLES IN THE HEAD
      ------------------------------------- */
      @media all {
        .ExternalClass {
          width: 100%; }
        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
          line-height: 100%; }
        .apple-link a {
          color: inherit !important;
          font-family: inherit !important;
          font-size: inherit !important;
          font-weight: inherit !important;
          line-height: inherit !important;
          text-decoration: none !important; } 
        .btn-primary table td:hover {
          background-color: #34495e !important; }
        .btn-primary a:hover {
          background-color: #FFF0A5 !important;
          border-color: #FFFDF1 !important; } }
    </style>
  </head>
  <body class="">
    <table border="0" cellpadding="0" cellspacing="0" class="body">
      <tr>
        <td>&nbsp;</td>
        <td class="container">
          <div class="content">

            <!-- START CENTERED WHITE CONTAINER -->
            <span class="preheader"><h3>Thanks for your order!</h3></span>

            <table class="main">

              <!-- START MAIN CONTENT AREA -->
              <tr>
                <td class="wrapper">
                    <img src="<?php echo base_url('assets/themes/minky-moon/img/minkymoonlogo.jpg'); ?>">
                    <h1>Thanks for your order!</h1>
                    <p>If you have any questions about your order or would like to see the progress please login to your account by clicking the "My Account" button below.</p> <p>Your order/transaction id is <strong><?php echo $transaction_id; ?></strong>, anytime you have a question about your order please refer to this number.</p>
                  <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td>

                          <table class="table">
                              <tr>
                                  <th>QTY</th>
                                  <th style="text-align:left">Item Description</th>
                                  <th style="text-align:right;width: 100px;">Item Price</th>
                                  <th style="text-align:right;width: 100px;">Sub-Total</th>
                              </tr>

                              <?php $i = 1; ?>

                              <?php foreach ($this->cart->contents() as $items): ?>


                                  <tr>
                                      <td>
                                          <?php echo $items['qty']; ?>

                                      </td>
                                      <td>
                                          <?php echo '<b>'.$items['name'].'</b>'; ?>

                                          <?php if ($this->cart->has_options($items['rowid']) == TRUE): ?>
                                              <p>

                                                  <?php
                                                  $personalization = '';
                                                  $features = '';
                                                  foreach ($this->cart->product_options($items['rowid']) as $option_name => $option_value){
                                                      if(is_array($option_value)) {
                                                          $personalization .= '<ul>';
                                                          foreach($option_value as $v) {
                                                              $personalization .= '<li><b>'.$v->name.'</b> '.$v->value.'</li>';
                                                          }
                                                          $personalization .= '</ul>';

                                                      } else {
                                                          if(isset($option_value->name)) {
                                                              $features .= '<strong>'.ucwords(str_replace('_' , ' ', $option_name)).':</strong> ';
                                                              $features .= ucwords($option_value->name).' - ';
                                                          } elseif($option_value) {
                                                              $personalization .= '<strong>'.ucwords(str_replace('_' , ' ', $option_name)).':</strong> ';
                                                              $personalization .= $option_value.' - ';
                                                          }
                                                      }
                                                  }

                                                  echo rtrim($features, ' - ').'<br>';
                                                  echo rtrim($personalization, ' - ');
                                                  ?>
                                              </p>
                                          <?php endif; ?>

                                      </td>
                                      <td style="text-align:right;">$<?php echo $this->cart->format_number($items['price']); ?></td>
                                      <td style="text-align:right;width: 100px;">$<?php echo $this->cart->format_number($items['subtotal']); ?></td>
                                  </tr>

                                  <?php $i++; ?>
                                    <tr><td colspan="4"><hr></td></tr>
                              <?php endforeach; ?>

                              <tr>
                                  <td colspan="4" class="right" style="text-align: right;" align="right"><strong>Total</strong> $<?php echo $this->cart->format_number($this->cart->total()); ?></td>
                              </tr>

                          </table>


                          <hr>
                          <table border="0" cellpadding="0" cellspacing="0">
                              <tbody>
                              <tr>
                                  <td>
                                      <table border="0" cellpadding="0" cellspacing="0" class="btn btn-primary">
                                          <tbody>
                                          <tr>
                                              <td align="left">
                                                  <table border="0" cellpadding="0" cellspacing="0">
                                                      <tbody>
                                                      <tr>
                                                          <td> <a href="<?php echo base_url('contact-us'); ?>" target="_blank">Contact Us</a> </td>
                                                      </tr>
                                                      </tbody>
                                                  </table>
                                              </td>
                                          </tr>
                                          </tbody>
                                      </table>
                                  </td>
                                  <td>
                                      <table border="0" cellpadding="0" cellspacing="0" class="btn btn-info right" align="right" style="text-align: right;">
                                          <tbody>
                                          <tr>
                                              <td align="right">
                                                  <table border="0" cellpadding="0" cellspacing="0">
                                                      <tbody>
                                                      <tr>
                                                          <td> <a href="<?php echo base_url('my-account'); ?>" target="_blank">My Account</a> </td>
                                                      </tr>
                                                      </tbody>
                                                  </table>
                                              </td>
                                          </tr>
                                          </tbody>
                                      </table>
                                  </td>
                              </tr>
                          </table>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>

            <!-- END MAIN CONTENT AREA -->
            </table>

            <!-- START FOOTER -->
            <div class="footer">
              <table border="0" cellpadding="0" cellspacing="0">
                  <?php
                    if(BUSINESS_ADDRESS != '') {
                        echo '<tr>';
                        echo '<td class="content-block">';
                        echo '<span class="apple-link">' . BUSINESS_ADDRESS . '</span>';
                        echo '</td>';
                        echo '</tr>';
                    }
                  ?>
                <tr>
                  <td class="content-block powered-by">
                    <a href="<?php echo base_url(); ?>"><?php echo BUSINESS_NAME; ?> All Rights Reserved</a>.
                  </td>
                </tr>
              </table>
            </div>
            <!-- END FOOTER -->
            
          <!-- END CENTERED WHITE CONTAINER -->
          </div>
        </td>
        <td>&nbsp;</td>
      </tr>
    </table>
  </body>
</html>