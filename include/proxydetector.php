<?php
/*
************************************************
*==========[TS Special Edition v.5.6]==========*
************************************************
*              Special Thanks To               *
*        DrNet - wWw.SpecialCoders.CoM         *
*          Vinson - wWw.Decode4u.CoM           *
*    MrDecoder - wWw.Fearless-Releases.CoM     *
*           Fynnon - wWw.BvList.CoM            *
*==============================================*
*   Note: Don't Modify Or Delete This Credit   *
*     Next Target: TS Special Edition v5.7     *
*     TS SE WILL BE ALWAYS FREE SOFTWARE !     *
************************************************
*/
if(!defined('IN_TRACKER'))
	die("<font face='verdana' size='2' color='darkred'><b>Error!</b> Direct initialization of this file is not allowed.</font>");
# Proxy Detector v.0.2 by xam
# This simple script detects that if a users is using a proxy server to connect to your website.

	function CheckForProxy($ip)
    {
		global $lang;
        $proxies = array( // This will hold our vast array of HTTP proxies.
            /*
                102 Elite Proxies.
            */
            '62.141.165.18', // Elite, Germany.
            '200.88.223.98', // Elite, Unknown.
            '195.78.228.24', // Elite, Unknown.
            '83.133.51.111', // Elite, Unknown.
            '83.133.48.196', // Elite, Unknown.
            '83.133.51.111', // Elite, Unknown.
            '83.133.48.196', // Elite, Unknown.
            '83.133.49.201', // Elite, Unknown.
            '201.234.158.8', // Elite, Unknown.
            '201.235.208.14', // Elite, Unknown.
            '200.42.83.11', // Elite, Argentina.
            '200.55.121.153', // Elite, Argentina.
            '201.253.223.116', // Elite, Argentina.
            '201.17.146.34', // Elite, Brazil.
            '201.17.174.58', // Elite, Brazil.
            '201.34.203.195', // Elite, Brazil.
            '200.223.211.205', // Elite, Brazil.
            '200.176.24.54', // Elite, Brazil.
            '201.17.22.71',    // Elite, Brazil.
            '201.17.70.147', // Elite, Brazil.
            '201.31.15.253', // Elite, Brazil.
            '201.36.165.50', // Elite, Brazil.
            '200.206.241.78', // Elite, Brazil.
            '201.17.198.159', // Elite, Brazil.
            '201.17.221.144', // Elite, Brazil.
            '201.17.227.142', // Elite, Brazil.
            '201.17.252.98', // Elite, Brazil.
            '201.37.36.216', // Elite, Brazil.
            '201.6.246.186', // Elite, Brazil.
            '200.210.129.238', // Elite, Brazil.
            '201.30.48.148', // Elite, Brazil.
            '201.31.102.130', // Elite, Brazil.
            '201.64.25.91', // Elite, Brazil.
            '200.72.162.219', // Elite, Chile.
            '202.106.192.42', // Elite, China.
            '219.144.132.150', // Elite, China.
            '222.89.164.54', // Elite, China.
            '219.149.54.34', // Elite, China.
            '202.117.8.199', // Elite, China.
            '200.21.60.158', // Elite, Colombia.
            '200.21.80.62', // Elite, Colombia.
            '201.247.104.85', // Elite, El Salvador.
            '195.101.121.28', // Elite, France.
            '82.232.97.239', // Elite, France.            
            '193.174.67.187', // Elite, Germany.            
            '131.188.44.100', // Elite, Germany.
            '212.227.93.20', // Elite, Germany.
            '138.246.99.249', // Elite, Germany.
            '138.246.99.250', // Elite, Germany.
            '195.37.16.97',    // Elite, Germany.            
            '61.238.244.86', // Elite, Hong Kong.
            '221.134.89.10', // Elite, India.
            '132.72.23.11',    // Elite, Israel.            
            '130.192.201.30', // Elite, Italy.
            '220.57.96.109', // Elite, Japan.
            '218.45.44.45',    // Elite, Japan.
            '61.120.143.110', // Elite, Japan.
            '219.164.244.202', // Elite, Japan.
            '219.206.196.134', // Elite, Japan.
            '220.24.76.66',    // Elite, Japan.
            '220.27.68.208', // Elite, Japan.
            '220.4.136.196', // Elite, Japan.
            '221.255.153.79', // Elite, Japan.
            '219.31.4.117',    // Elite, Japan.
            '219.49.198.6',    // Elite, Japan.
            '221.117.135.143', // Elite, Japan.
            '221.86.225.36', // Elite, Japan.
            '59.171.117.150', // Elite, Japan.
            '61.24.156.194', // Elite, Japan.            
            '61.103.229.36', // Elite, Korea.
            '221.152.208.242', // Elite, Korea.
            '165.194.121.227', // Elite, Korea.
            '211.178.7.70', // Elite, Korea.
            '218.51.47.3', // Elite, Korea.
            '219.241.127.209', // Elite, Korea.
            '220.73.24.25',    // Elite, Korea.
            '221.138.90.224', // Elite, Korea.
            '221.149.98.173', // Elite, Korea.
            '222.238.179.10', // Elite, Korea.
            '58.121.51.88',     // Elite, Korea.
            '58.227.206.161', // Elite, Korea.
            '58.239.74.82',    // Elite, Korea.
            '220.89.82.152', // Elite, Korea.
            '124.57.2.53', // Elite, Korea.
            '125.136.76.106', // Elite, Korea.
            '125.190.240.227', // Elite, Korea.
            '203.236.103.196', // Elite, Korea.
            '211.116.67.133', // Elite, Korea.
            '220.85.241.142', // Elite, Korea.
            '220.88.112.227', // Elite, Korea.
            '221.159.252.235', // Elite, Korea.
            '58.151.21.35', // Elite, Korea.
            '58.73.242.79',    // Elite, Korea.
            '58.77.206.177', // Elite, Korea.
            '59.10.198.23', // Elite, Korea.
            '59.15.50.100',    // Elite, Korea.
            '59.187.123.54', // Elite, Korea.
            '59.22.16.123', // Elite, Korea.
            '59.26.62.38', // Elite, Korea.
            '61.106.183.108', // Elite, Korea.
            '61.38.105.70',    // Elite, Korea.
            '61.43.99.66', // Elite, Korea.    
            /*
                20 Anonymous Proxies.
            */                        
            '203.28.36.73', // Anonymous, Australia.
            '202.130.193.194', // Anonymous, Australia.            
            '212.35.114.108', // Anonymous, Australia.    
            '201.3.169.170', // Anonymous, Brazil.
            '211.153.44.155', // Anonymous, China.
            '218.11.207.244', // Anonymous, China.
            '60.191.251.9',    // Anonymous, China.
            '202.101.6.85',    // Anonymous, China.
            '219.136.249.79', // Anonymous, China.
            '61.185.219.235', // Anonymous,China.
            '213.136.105.2', // Anonymous, Cote DIvoire.
            '200.88.223.98', // Anonymous, Dominican Republic.
            '217.77.78.61',    // Anonymous, Gabon.
            '85.212.161.75', // Anonymous, Germany.
            '213.147.3.80',    // Anonymous, Germany.
            '81.75.43.106',    // Anonymous, Italy.
            '211.121.50.23', // Anonymous, Japan.
            '219.113.147.165', // Anonymous, Japan.
            '80.241.33.107', // Anonymous, Kazakstan.
            '81.69.118.53',    // Anonymous, Netherlands.        
            /*
                48 PlanetLab Proxies.
            */
            '130.104.72.200', // PlanetLab, Belgium.
            '130.104.72.201', // PlanetLab, Belgium.
            '150.165.15.19', // PlanetLab, Brazil.
            '200.129.0.162', // PlanetLab, Brazil.
            '142.103.2.1', // PlanetLab, Canada.
            '206.12.16.133', // PlanetLab, Canada.
            '198.163.152.229', // PlanetLab, Canada.
            '198.163.152.230', // PlanetLab, Canada.
            '205.189.33.178', // PlanetLab, Canada.
            '129.97.75.240', // PlanetLab, Canada.
            '132.204.102.20', // PlanetLab, Canada.
            '192.197.121.3', // PlanetLab, Canada.
            '194.42.17.123', // PlanetLab, Cyprus.
            '194.42.17.124', // PlanetLab, Cyprus.
            '192.38.109.143', // PlanetLab, Denmark.
            '128.214.112.91',// PlanetLab, Finland.
            '193.167.182.130',// PlanetLab, Finland.
            '193.174.67.186', // PlanetLab, Germany.
            '193.174.67.187', // PlanetLab, Germany.
            '130.149.49.28', // PlanetLab, Germany.
            '212.201.44.74', // PlanetLab, Germany.
            '130.83.160.199', // PlanetLab, Germany.
            '130.83.160.200', // PlanetLab, Germany.
            '131.188.44.100', // PlanetLab, Germany.
            '132.252.152.193', // PlanetLab, Germany.
            '132.252.152.194', // PlanetLab, Germany.
            '130.75.87.84', // PlanetLab, Germany.
            '141.24.33.162', // PlanetLab, Germany.
            '131.234.66.160', // PlanetLab, Germany.
            '195.37.16.101', // PlanetLab, Germany.
            '134.2.202.227', // PlanetLab, Germany.
            '134.2.202.228', // PlanetLab, Germany.
            '139.19.142.2', // PlanetLab, Germany.
            '139.19.142.3', // PlanetLab, Germany.
            '139.19.142.4',    // PlanetLab, Germany.
            '139.19.142.5', // PlanetLab, Germany.
            '139.19.142.6', // PlanetLab, Germany.
            '193.6.20.4', // PlanetLab, Hungary.
            '193.1.201.27', // PlanetLab, Ireland.
            '132.72.23.10', // PlanetLab, Israel.
            '131.175.17.10', // PlanetLab, Italy.
            '163.221.11.71', // PlanetLab, Japan.
            '133.11.240.56', // PlanetLab, Japan.
            '133.11.240.57', // PlanetLab, Japan.
            '210.125.84.15', // PlanetLab, Korea.
            '210.125.84.16', // PlanetLab, Korea.
            '143.248.139.169', // PlanetLab, Korea.
            '210.107.249.51' // PlanetLab, Korea.
        );
        
        $web_proxies = array( // This will hold our vast array of web proxies.
            /*
                80 Web Proxies.
            */
            '85.195.123.22', // Anonymouse.
            '85.195.119.14', // Anonymouse.
            '216.127.74.88', // AnonymousIndex.
            '208.53.131.140', // 78Y.
            '38.118.72.221', // AntiFirewall.
            '209.172.59.238', // ProxyHero.
            '206.51.229.186', // NinjaProxy.
            '66.225.253.130', // Prx1.
            '72.232.68.98', // The Proxy.
            '64.27.5.168', // Proxy Spy.
            '72.29.67.118', //  Pimp My Ip.
            '10.130.98.120', // Proxify.
            '66.90.73.133', // 163.
            '69.80.225.11', // Polysolve.
            '127.98.66.223', // Virtual-browser.
            '64.78.164.226', // Iphide.
            '66.90.73.113', // SlimTrust.
            '65.98.6.162', // Proxy-site.
            '207.36.196.108', // Gecko-proxy.
            '64.72.127.155', // Ipbounce.
            '66.7.194.113', // Sneakonline.
            '69.50.235.82', // Thaproxy.
            '64.72.127.155', // Xxxproxy.
            '207.234.209.125', // Unipeak.
            '66.90.73.113', // MathTunnel.
            '64.92.163.234', // Proxysurfing.
            '127.98.66.20', // Perlproxy.
            '70.86.157.90', // Tnproxy.
            '62.212.83.94', // Bypassfilter.
            '208.101.10.53', // Unblockonline.
            '64.202.165.132', // Proxyology.
            '62.212.83.94', // Secret-filter.
            '62.212.83.94', // Thesecreturl.
            '62.212.83.94', // Urlport.
            '209.9.228.194', // Unistealth.
            '72.9.105.18', // Proxyservices.
            '208.113.136.24', // Freshtart.
            '64.27.0.166', // Proxydrop.
            '66.226.73.53', // Stealth-ip.
            '80.74.241.31', // Flatline.
            '208.113.136.16', // Proxyace.
            '64.111.110.10', // Browse-from-work.
            '64.27.0.166', // Fullysickproxy.
            '62.212.83.94', // Silentproxy.
            '66.79.167.235', // 2leftnuts.
            '208.101.10.53', // Ergoproxy1.
            '66.90.101.225', // Newbackdoor.
            '208.113.136.24', // Modernproxy.
            '69.36.166.207', // Proxyfull.
            '66.197.236.197', // Fastsec.
            '208.101.10.50', // Bypasslive.
            '65.98.6.162', // Fastproxy.
            '216.240.134.126', // Runproxy.
            '65.98.114.162', // Adultproxy.
            '66.90.101.225', // Englishtunnel.
            '209.9.228.194', // Duoproxy.
            '70.86.108.146', // W00tage.
            '82.165.253.19', // Coveredtracks.
            '64.74.153.230', // V3proxy.
            '66.79.167.235', // Mypr0xy.
            '208.101.8.98', // Proxyd.
            '209.85.15.32', // Pruxys.
            '64.27.0.166', // Aproxysite.
            '80.86.82.209', // Setproxy.
            '10.131.98.12', // Proxify.
            '66.98.138.80', // Provacy.
            '62.212.83.94', // Covertproxy.
            '213.186.45.33', // Anonymizer1.
            '66.79.167.219', // MySpace Bypass.
            '62.212.83.94', // Proxyurl.
            '70.86.157.90', // Schoolmyspace.
            '72.9.105.18', // Your-proxy.
            '66.197.201.70', //  Zidev.
            '62.212.83.94', // Craftyproxy.
            '62.212.83.94', // Anonfind.
            '127.98.66.223', // Torify.
            '64.27.5.187', // Asproxy.
            '65.110.6.40', // Snoopblocker.
            '207.210.234.135', // Surf-anon.
            '66.246.218.240' // Proxy8.
        );
        
        if(in_array($ip, $proxies, true) OR in_array($ip, $web_proxies, true)) 
			stderr($lang->global['error'], $lang->global['proxydetected']);
		elseif((!empty($_SERVER['HTTP_VIA'])) || (!empty($_SERVER['HTTP_FROM'])) ||
		(!empty($_SERVER['HTTP_EXTENSION'])) || (!empty($_SERVER['HTTP_CLIENT_IP'])) ||
		(!empty($_SERVER['HTTP_FORWARDED'])) || (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) ||
		(!empty($_SERVER['HTTP_MAX_FORWARDS'])) || (!empty($_SERVER['HTTP_X_FORWARDED_SERVER'])) ||
		(!empty($_SERVER['HTTP_X_FORWARDED_SERVER'])) || (!empty($_SERVER['HTTP_CACHE_INFO'])) ||
		(!empty($_SERVER['HTTP_PROXY_CONNECTION'])) || (!empty($_SERVER['HTTP_XROXY_CONNECTION'])))
			stderr($lang->global['error'], $lang->global['proxydetected']);
    } 
?>
