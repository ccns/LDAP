    <footer>
        <div class="footer-content width">
            <ul>
            	<li><h4></h4></li>
                <li><a href="#"></a></li>
            </ul>
            
            <ul class="endfooter">
            	<li><h4></h4></li>
                <li><a href="#"></a></li>
            </ul>
            
            <div class="clear"></div>
        </div>
        <div class="footer-bottom">
            <p>&copy; CCNS 2014.</p>
         </div>
    </footer>
</div>
<img id="mimg" src="/img/m.png" class="hide" />
<img id="loader" src="/img/loader.gif" class="hide" />
<!-- end of container -->
</body>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="//cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js"></script>
<script src="/js/jquery.sp.js"></script>
<script src="/js/main.js"></script>
<?php if(isset($tab['user'])): ?>
<script src="/js/user.js"></script>
<?php elseif(isset($tab['new_user'])): ?>
<script src="/js/new_user.js"></script>
<?php elseif(isset($tab['forgot_pw'])): ?>
<script src="/js/forgot_pw.js"></script>
<?php elseif(isset($tab['member_directory'])): ?>
<script src="/js/directory.js"></script>
<?php endif; ?>
</html>
