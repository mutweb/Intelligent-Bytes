<?php
	// file get content
	$url = 'https://api.jsonbin.io/b/61a86f4462ed886f91587e1a/1';
	$text = file_get_contents($url);
	$array = json_decode($text, true);

	$list = isset($array['books']) ? $array['books'] : array();
?>
<!doctype html>
<html lang="th">
<head>
	<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Rungroj Madisara (0814415656) for Intelligent-Bytes</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<div id="layout">
<?php
	define('BOOK_PRICE', 100);
	$result = array();// store result to display
	if( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
		$discount = 0;	// Initial % of discount
		$sum_book = 0;	// Initial sum book
		$factor = 1; 		// Factor multiple discount
		$amount = $_POST['amount'];
		if( is_array($amount) ) {
			foreach($amount as $k => $v) {
				if( !$v ) {
					unset($amount[$k]);
				}else {
					$sum_book += $v;
				}
			}
			$result['sum_book'] = $sum_book;

			// Check type of book to assume discount%
			$num = count($amount);
			if( $num > 1 ) {
				$factor = min($amount);
				$discount = ($num - 1) * 10;
			}

			$result['sum_discount'] = (BOOK_PRICE * $num * ($discount * $factor / 100));
			$result['sum_price'] = BOOK_PRICE * $sum_book;
			
		}
	}
?>
<form method="post">
<?php if( $result ) { ?>
<table class="table">
  <tr>
    <th scope="row">รวม <?php echo number_format($result['sum_book']); ?> เล่ม</th>
    <th scope="row">ราคารวม <?php echo number_format($result['sum_price']); ?> บาท</th>
    <th scope="row">ส่วนลด <?php echo number_format($result['sum_discount']); ?> บาท</th>
    <th scope="row">รวมสุทธิ <?php echo number_format($result['sum_price'] - $result['sum_discount']); ?> บาท</th>
  </tr>
</table>

<?php } ?>
<table class="table table-hover">
  <thead class="thead-dark">
    <tr>
      <th scope="col">#</th>
      <th scope="col">Photo</th>
      <th scope="col">Name</th>
      <th scope="col">Volume</th>
    </tr>
  </thead>
  <tbody>
  	<?php foreach( $list as $l ) { ?>
    <tr>
      <td scope="row"><?php echo $l['id']; ?></td>
      <td><img src="<?php echo $l['cover']; ?>" width="100"></td>
      <td><?php echo $l['title']; ?></td>
      <td><input type="number" name="amount[<?php echo $l['id']; ?>]" min="0" max="100" maxlength="3" value="0" class="form-control"></td>
    </tr>
    <?php } ?>
  </tbody>
  <tfoot class="thead-dark">
    <tr>
      <td colspan="4">
      	<div style="text-align:center"><input type="submit" value="คำนวณ" id="btnCalculate" class="btn btn-primary"></div>
      </td>
    </tr>
  </tfoot>  
</table>
</form>

<script>
	/* Capture Event */
	$('#btnCalculate').click(function(e){ 
		_sum = 0;
		/* Loop Count Check zero */
		$(this).closest('table').find('input[type="number"]').each(function(){
			_sum += parseInt($(this).val());
		});
		
		if( _sum > 0 ) {
			return true;
		}else {
			e.preventDefault();
			alert('กรุณาระบุจำนวนหนังสือ!');
		}
	});
</script>

</div>

</body>
</html>