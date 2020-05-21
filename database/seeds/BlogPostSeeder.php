<?php

use App\BlogPost;
use Illuminate\Database\Seeder;

class BlogPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // factory(BlogPost::class, 50)->create();

        factory(BlogPost::class)->create([
            'blog_image' => 'images/blogposts/cyber_security_abstract_wall_of_locks_padlocks_by_blackjack3d_gettyimages_1140211995-100817782-large.jpg',
            'body' => '<h3>The traditional VPN is being replaced by a smarter, safer approach to network security that treats everyone as equally untrusted.</h3>
            <p>The venerable VPN, which has for decades provided remote workers with a secure tunnel into the enterprise network, is facing extinction as enterprises migrate to a more agile, granular security framework called zero trust, which is better adapted to today&rsquo;s world of digital business.</p>
            <p>VPNs are part of a security strategy based on the notion of a network perimeter; trusted employees are on the inside and untrusted employees are on the outside. But that model no longer works in a modern business environment where mobile employees access the network from a variety of inside or outside locations, and where corporate assets reside not behind the walls of an enterprise data center, but in multi-cloud environments.</p>
            <p>Gartner predicts that by 2023, 60% of enterprises will phase out most of their VPNs in favor of zero trust network access, which can take the form of a gateway or broker that authenticates both device and user before allowing role-based, context-aware access.</p>
            <p>There are a variety of flaws associated with the perimeter approach to security. It doesn&rsquo;t address insider attacks. It doesn&rsquo;t do a good job accounting for contractors, third parties and supply-chain partners. If an attacker steals someone&rsquo;s VPN credentials, the attacker can access the network and roam freely. Plus, VPNs over time have become complex and difficult to manage. &ldquo;There&rsquo;s a lot of pain around VPNs,&rdquo; says Matt Sullivan, senior security architect at Workiva, an enterprise software company based in Ames, Iowa. &ldquo;They&rsquo;re clunky, outdated, there&rsquo;s a lot to manage, and they&rsquo;re a little dangerous, frankly.&rdquo;&nbsp;&nbsp;</p>
            <p>At an even more fundamental level, anyone looking at the state of enterprise security today understands that whatever we&rsquo;re doing now isn&rsquo;t working. &ldquo;The perimeter-based model of security categorically has failed,&rdquo; says Forrester principal analyst Chase Cunningham. &ldquo;And not from a lack of effort or a lack of investment, but just because it&rsquo;s built on a house of cards. If one thing fails, everything becomes a victim. Everyone I talk to believes that.&rdquo;</p>
            <p>Cunningham has taken on the zero-trust mantle at Forrester, where analyst Jon Kindervag, now at Palo Alto Networks, developed a zero-trust security framework in 2009. The idea is simple: trust no one. Verify everyone. Enforce strict access-control and identity-management policies that restrict employee access to the resources they need to do their job and nothing more.</p>
            <aside id="" class="nativo-promo nativo-promo-1 smartphone"></aside>
            <p>Garrett Bekker, principal analyst at the 451 Group, says zero trust is not a product or a technology; it&rsquo;s a different way of thinking about security. &ldquo;People are still wrapping their heads around what it means. Customers are confused and vendors are inconsistent on what zero trust means. But I believe it has the potential to radically alter the way security is done.&rdquo;</p>
            <h2>Security vendors embrace zero trust</h2>
            <p>Despite the fact that the zero-trust framework has been around for a decade, and has generated quite a bit of interest, it has only been in the last year or so that enterprise adoption has begun to take off. According to a recent 451 Group survey, only around 13% of enterprises have even started down the road to zero trust. One key reason is that vendors have been slow to step up.</p>
            <p>The poster boy success story for zero trust dates back to 2014, when Google announced its BeyondCorp initiative. Google invested untold amounts of time and money building out its own zero-trust implementation, but enterprises were unable to follow suit because, well, they weren&rsquo;t Google.</p>
            <p>But zero trust is now gaining traction. &ldquo;The technology has finally caught up to the vision,&rdquo; says Cunningham. &ldquo;Five to seven years ago we didn&rsquo;t have the capabilities that could enable these types of approaches. We&rsquo;re starting to see that it&rsquo;s possible.&rdquo;</p>
            <p>Today, vendors are coming at zero trust from all angles. For example, the latest Forrester Wave for what it now calls the zero-trust eXtended Ecosystem (ZTX) includes next-generation firewall vendor Palo Alto Networks, managed-services provider Akamai Technologies, identity-management vendor Okta, security-software leader Symantec, micro-segmentation specialist Illumio, and privileged-access management vendor Centrify.</p>
            <p>Not to be left out, Cisco, Microsoft and VMware all have zero-trust offerings. According to the Forrester Wave, Cisco and Microsoft are classified as strong performers and VMware is a contender.</p>
            <aside class="nativo-promo nativo-promo-2 tablet desktop smartphone"></aside>
            <p>So, how does an enterprise, which has devoted millions of dollars to building and reinforcing its perimeter defenses, suddenly shift gears and adopt a model that treats everyone, whether an executive working inside corporate headquarters or a contractor working from a Starbucks, as equally untrusted?</p>
            <h2>How to get started with a zero-trust security model</h2>
            <p>The first and most obvious recommendation is to start small, or as Cunningham puts it, &ldquo;try to boil a thimble of water and not the whole ocean.&rdquo; He adds, &ldquo;For me, the first thing would be to take care of vendors and third parties,&rdquo; finding a way to isolate them from the rest of the network.</p>
            <p>Gartner analyst Neil MacDonald agrees. He identifies three emerging use cases for zero trust: new mobile applications for supply chain partners, cloud migration scenarios and access control for software developers.</p>
            <p>Access control for his DevOps and IT operations groups is exactly what Sullivan implemented at Workiva, a company whose IT infrastructure is entirely cloud-based. Sullivan was looking for a more effective way to give his teams cloud access to specific development and staging instances. He ditched his traditional VPN in favor of zero-trust access control from ScaleFT, a startup that was recently acquired by Okta.</p>
            <p>Sullivan says that now when a new employee gets a laptop, that device needs to be explicitly authorized by an admin. To access the network, the employee connects to a central gateway that applies the appropriate identity- and access-management policies.</p>
            <p>&ldquo;Zero trust as a concept was so overdue,&rdquo; says Sullivan. &ldquo;It&rsquo;s clearly the right way to go, yet it took us nearly 10 years of whining and complaining before enterprise-ready solutions came out.&rdquo;</p>
            <p>&nbsp;</p>',
            'title' => 'The VPN is dying, long live zero trust',
            'author_id' => 1
        ]);


        factory(BlogPost::class)->create([
            'blog_image' => 'images/blogposts/cso_nw_cloud_computing_cloud_network_circuits_by_denis_isakov_gettyimages-966932508_2400x1600-100814451-large.jpg',
            'body' => '<h3>The 2019 ThousandEyes Benchmark report shows that not all cloud providers are created equal across all regions</h3>
            <p>Not all public cloud service providers are the same when it comes to network performance.</p>
            <p>Each one&rsquo;s connectivity approach varies, which causes geographical discrepancies in network performance and predictability. As businesses consider moving to the cloud, especially software-defined wide-area networks (<a href="https://www.networkworld.com/article/3031279/sd-wan-what-it-is-and-why-you-ll-use-it-one-day.html">SD-WAN</a>) and&nbsp;<a href="https://www.networkworld.com/article/3429258/real-world-tools-for-multi-cloud-management.html">multi-cloud</a>, it&rsquo;s important to understand what each public cloud service provider brings to the table and how they compare.</p>
            <p>In 2018, ThousandEyes first conducted&nbsp;<a href="https://www.networkworld.com/article/3319776/the-network-matters-for-public-cloud-performance.html">a benchmark study assessing three major public cloud providers</a>: Amazon Web Services (AWS), Microsoft Azure (Azure), and Google Cloud Platform (GCP). The study gathered data on network performance and connectivity architecture to guide businesses in the planning stage.</p>
            <p><a href="https://www.thousandeyes.com/press-releases/second-annual-cloud-performance-benchmark-research" rel="nofollow">This year&rsquo;s study</a>&nbsp;offers a more comprehensive view of the competition, with two more providers added to the list: Alibaba Cloud and IBM Cloud. It compares 2018 and 2019 data to show changes that took place year-over-year and what triggered them.</p>
            <p>ThousandEyes periodically collected bi-directional network performance metrics&mdash;such as latency, packet loss and jitter&mdash;from 98 user vantage points in global&nbsp;<a href="https://www.networkworld.com/article/3223692/what-is-a-data-centerhow-its-changed-and-what-you-need-to-know.html">data centers</a>&nbsp;across all five public cloud providers over a four-week period. Additionally, it looked at network performance from leading U.S. broadband internet service providers (ISPs), including AT&amp;T, Verizon, Comcast, CenturyLink, Cox, and Charter.</p>
            <p>The network management company then analyzed more than 320 million data points to create the benchmark. Here are the results.</p>
            <aside id="" class="nativo-promo nativo-promo-1 smartphone"></aside>
            <h2>Inconsistencies among providers</h2>
            <p>In its initial study, ThousandEyes revealed that some cloud providers rely heavily on the public internet to carry user traffic while others don&rsquo;t. In this year&rsquo;s study, the cloud providers generally showed similar performance in bi-directional network latency.</p>
            <p>However, ThousandEyes found architectural and connectivity differences have a big impact on how traffic travels between users and certain cloud hosting regions. AWS and Alibaba mostly rely on the internet to transport user traffic. Azure and GCP use their private backbone networks. IBM is different from the rest and takes a hybrid approach.</p>
            <aside id="" class="nativo-promo nativo-promo-1 tablet desktop"></aside>
            <p>ThousandEyes tested the theory of whether AWS Global Accelerator out-performs the internet. AWS Global Accelerator launched in November 2018, offering users the option to utilize the AWS private backbone network for a fee instead of the default public internet. Although performance did improve in some regions around the world, there where other instances where the internet was faster and more reliable than AWS Global Accelerator.</p>
            <p>Broadband ISPs that businesses use to connect to each cloud also showed inconsistencies, even in the mature U.S. market. After evaluating network performance from the six U.S. ISPs, sub-optimal routing results were recorded, with up to 10 times the expected network latency in some cases.</p>
            <p><strong>Location, location, location</strong></p>
            <p>Cloud providers commonly experience packet loss when crossing through China&rsquo;s content-filtering firewall, even those from the region like Alibaba. The 2019 study closely examined the performance toll cloud providers pay in China, which has a notoriously challenging geography for online businesses. For those with customers in China, ThousandEyes recommends Hong Kong as a hosting region since Alibaba Cloud traffic experienced the least packet loss there, followed by Azure and IBM.</p>
            <aside id="" class="nativo-promo nativo-promo-2 tablet desktop smartphone"></aside>
            <p>In other parts of the world, Latin America and Asia showed the highest performance variations for all cloud providers. For example, network latency was six times higher from Rio de Janeiro to GCP&rsquo;s S&atilde;o Paulo hosting region because of a suboptimal reverse path, compared to other providers. But across North America and Western Europe, all five cloud providers demonstrated comparable, robust network performance.</p>
            <p>The study&rsquo;s results confirm that location is a major factor, therefore, user-to-hosting-region performance data should be considered when selecting a public cloud provider.</p>
            <p><strong>Multi-cloud connectivity</strong></p>
            <p>In 2018, ThousandEyes discovered extensive connectivity between the backbone networks of AWS, GCP, and Azure. An interesting finding in this year&rsquo;s study shows multi-cloud connectivity was erratic when IBM and Alibaba Cloud were added to the list.</p>
            <p>ThousandEyes found IBM and Alibaba Cloud don&rsquo;t have fully established, direct connectivity with other providers. That&rsquo;s because they typically use ISPs to connect their clouds to other providers. AWS, Azure, and GCP, on the other hand, peer directly with each other and don&rsquo;t require third-party ISPs for multi-cloud communication.</p>
            <p>With multi-cloud initiatives on the rise, network performance should be included as a metric in evaluating multi-cloud connectivity since it appears to be inconsistent across providers and geographical boundaries.</p>
            <p>ThousandEyes&rsquo; comprehensive performance benchmark can serve as a guide for businesses deciding which public cloud provider best meets their needs. But to err on the side of caution, businesses selecting public cloud connectivity should consider the unpredictable nature of the internet, how it affects performance, creates risk, and increases operational complexity. Businesses should address those challenges by gathering their own network intelligence on a case-by-case basis. Only then they will benefit fully from what cloud providers have to offer.</p>
            <div class="end-note">
            <div id="" class="blx blxParticleendnote blxM2005  blox4_html  blxC23909">Join the Network World communities on&nbsp;<a href="https://www.facebook.com/NetworkWorld/" target="_blank" rel="noopener">Facebook</a>&nbsp;and&nbsp;<a href="https://www.linkedin.com/company/network-world" target="_blank" rel="noopener">LinkedIn</a>&nbsp;to comment on topics that are top of mind.</div>
            </div>',
            'title' => 'How cloud providers performance differs',
            'author_id' => 1
        ]);

        factory(BlogPost::class)->create([
            'blog_image' => 'images/blogposts/srv6.png',
            'body' => '<p>Segment Routing MPLS customer adoption is truly outstanding. As of today, we count more than 30 live deployments and about 80 deployments are planned.</p>
            <p>The pace of adoption for SRv6 will be even faster. Let me expand on why I&rsquo;m making this bet.</p>
            <p><strong><em>IPv6 connectivity is getting more pervasive</em></strong></p>
            <p>Back in 2012, major Internet service providers, home networking equipment manufacturers, and web companies around the world made the common decision to enable IPv6 for their products and services. Since then, IPv6 adoption has been steadily increasing, but at a much lower growth rate than expected. The upcoming fourth industrial revolution fueled by 5G connectivity is set to change the game and accelerate IPv6 deployments.</p>
            <p>As Asia, Europe, North America, and Latin America have already exhausted their IPv4 allotments, and Africa is expected to exhaust its allotment by 2019, the transition from an IPv4 environment to an IPv6 environment is becoming of utmost importance.</p>
            <p>IPv6 is the new normal and many service providers are actively engaged in upgrading their network infrastructures.</p>
            <p><strong><em>&nbsp;</em></strong></p>
            <p><strong><em>SRv6 inherits all of SR MPLS capabilities</em></strong></p>
            <p><em>Simplification</em></p>
            <p>SRv6 further simplifies the network by eliminating MPLS altogether and by relying on the native IPv6 header and header extension to provide the same services and flexibility as SR-MPLS, directly over the IPv6 data plane.</p>
            <p><em>Resiliency</em></p>
            <p>Resiliency plays a pivotal role in ensuring the network stays up always so that customers can access their services from anywhere at any time.</p>
            <p>Current routing protocols &ndash; IS-IS, OSPF &ndash; provide a first level of resiliency by rerouting traffic around failures in the network. But it&rsquo;s not enough, more and more applications need the network to guarantee under 50ms protection against any kind of network failures. This is exactly what SRv6 TI-LFA (Topology Independent Loop Free Alternate) brings with 100% topology coverage, simplicity and path optimality.</p>
            <p><em>Traffic Engineering</em></p>
            <p>Leveraging the most advanced SRv6 traffic engineering capabilities, the network can be turned into a multi-service infrastructure. New Flexible Algorithm capabilities make multiple optimizations of the same physical network infrastructure along various dimensions possible (e.g., one can be optimized for low-latency vs. another one for bandwidth, or one can offer disjoint paths via two distinct planes.)</p>
            <p>Network slicing will play a major role as service providers and enterprises get ready to offer a wide range of 5G services, that have specific and differentiated needs, over a converged infrastructure. As a result, service providers are implementing top-notch traffic engineering solutions across their network, directly from the cell site and up to the core and data centers, to ensure each service gets its own dedicated networking slice with its own set of SLAs.</p>
            <p>&nbsp;</p>
            <p><strong><em>SRv6 adds network programming capabilities</em></strong></p>
            <p>SRv6 takes advantage of IPv6 Extension Headers by inserting Segment Routing headers into IPv6 packets. Any IPv6 packet can now contain a list of Segment Identifiers (Segment IDs), that are nothing else than 128-bits IPv6 addresses. Thanks to the increase in Segment ID size, it is now possible to pack more than mere IP addresses into a Segment ID and hence go beyond routing purposes.</p>
            <p>This opens the door to infrastructure programming. The first 64 bits can be used to direct traffic to a specific node in the network &ndash; the&nbsp;<em>&ldquo;main body&rdquo;</em>&nbsp;of the program &ndash; the next 32 bits can be used to enforce some actions on the traffic &ndash; the&nbsp;<em>&ldquo;function&rdquo;</em>&nbsp;part &ndash; and the remaining 32 bits can be used to pass some additional information &ndash; the&nbsp;<em>&ldquo;argument&rdquo;</em>&nbsp;part.</p>
            <p>Supercharging the network with these programming capabilities is a game-changer in the way the network treats applications. Your network is no longer merely routing traffic from point A to point B according to some specific constraints expressed by applications (e.g., SR traffic engineering). The network can now take actions on the applications along the same path applications are transported over. It&rsquo;s about making your applications and your network interact in a completely different, new way.</p>
            <p><strong><em>&nbsp;</em></strong></p>
            <p><strong><em>SRv6 is enjoying strong adoption</em></strong></p>
            <p>SRv6 early adoption has been remarkable with some noteworthy live deployments supporting significant customer traffic:</p>
            <ul>
            <li><strong>SoftBank Corporation</strong>&nbsp;aims to further enhance the efficiency and functionality of its network by&nbsp;<a href="https://newsroom.cisco.com/press-release-content?type=webcontent&amp;articleId=1969030">introducing the latest technologies such as SRv6</a>&nbsp;with the intent to roll out a highly reliable mobile network that can cope with the future traffic for the age of 5G and IoT.</li>
            <li><strong>Iliad</strong>: The&nbsp;<a href="https://newsroom.cisco.com/press-release-content?type=webcontent&amp;articleId=1978361">SRv6 deployment</a>&nbsp;enables Iliad to build a network that is extremely scalable with improved reliability, flexibility, and agility, all while helping to reduce CapEx and OpEx. To further expand the SRv6 benefits across the entire network, Iliad has developed its own SRv6 software stack that will equip its homegrown &ldquo;NodeBox&rdquo; designed to aggregate the traffic from mobile base stations.</li>
            <li><strong>LINE Corporation</strong>&nbsp;uses an&nbsp;<a href="https://speakerdeck.com/line_developers/line-data-center-networking-with-srv6">SRv6 overlay</a>&nbsp;to provide per-service policy on a shared underlay network in the data center. The hypervisor takes care of encap/decap, and OpenStack is used as the SRv6 control plane with in-house developed extensions.</li>
            </ul>
            <p><strong>&nbsp;</strong></p>
            <p><strong><em>SRv6 ecosystem is growing</em></strong></p>
            <p>Any new technology can only make inroads into service providers&rsquo; networks when it is supported by a rich ecosystem; otherwise, the risks associated with rolling out a new technology are simply too high.</p>
            <p>SRv6 is benefiting from a rich ecosystem spanning across:</p>
            <ul>
            <li>Networking vendors &ndash; Nokia, Huawei</li>
            <li>Silicon chipset manufacturers &ndash; Barefoot, Broadcom</li>
            <li>SmartNic manufacturers &ndash; Intel</li>
            <li>Open source &ndash; Fd.io, Snort&hellip;</li>
            </ul>
            <p>It does not stop there. In the next coming months, you will hear about more companies taking advantage of these opportunities.</p>
            <p>&nbsp;</p>
            <p><strong><em>SRv6 standardization is well on its way</em></strong></p>
            <p>A key milestone was achieved in October 2019 with the Segment Routing Extension Header (SRH) draft proposal that became a proposed standard.</p>
            <p>Another key proposal is&nbsp;<a href="https://tools.ietf.org/html/draft-filsfils-spring-srv6-network-programming-07">SRv6 Network Programming</a>. While it is still in draft, it is in its advanced stages and could become a proposed standard as early as March 2020.</p>
            <p>Finally, new SRv6 network programming capabilities, such as the&nbsp;<a href="https://datatracker.ietf.org/doc/draft-filsfils-spring-net-pgm-extension-srv6-usid/"><em>SRv6 uSID instruction</em></a>, have been posted.&nbsp; They provide for ultra large-scale deployments (multi-domain networks with 100k routing nodes or datacenters with billions of servers) and leverage legacy equipment.</p>
            <p>If you want to find out more about the current state of SRv6 standardization, read&nbsp;<a href="http://www.segment-routing.net/updates-20191029-srv6-state/">this comprehensive update</a>.</p>
            <p>&nbsp;</p>
            <p>Over the next coming weeks, you will hear from some industry leaders who will share their thoughts on SRv6 &ndash; why they believe it is a game changer, and what their plans are.</p>
            <p>In the meantime, if you want to learn more about SRv6, visit us at&nbsp;<a href="https://www.segment-routing.net/">segment-routing.net</a>.</p>',
            'title' => 'SRv6 is coming',
            'author_id' => 1
        ]);


        factory(BlogPost::class)->create([
            'blog_image' => 'images/blogposts/5g-drivers-seat.png',
            'body' => '<p>The enterprise offers boundless opportunities as we move into and through the 5G era. 90% of Service Provider CXOs said the most important new revenue streams in 5G are going to come from enterprise.</p>
            <p>But what are the business models for 5G where the enterprise is concerned? And did anyone actually ask the enterprise if they&rsquo;re even interested in 5G?</p>
            <p>We did and they are. Cisco surveyed our enterprise customers and asked them what they wanted to receive from a 5G experience, and these are just some of the things they told us they expect:</p>
            <p>&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;More flexibility, control and visibility from their Service Providers.<br />&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;A network that&rsquo;s easy to operate and deploy.<br />&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;The ability to drive their network, based on policy (intent-based networking).</p>
            <p><strong>Wi-Fi 6 or 5G?</strong></p>
            <p>So, does the enterprise want Wi-Fi 6 or 5G? Is it a binary decision? The answer that came back from our enterprise customers is that they want the right tool for the job, whatever the job may be. We&rsquo;ve been working to break down the characteristics of each access type to understand what the right tool is for each job, so that we can advise our enterprise customers accordingly, and put them on the road to success.</p>
            <p>Enterprises are digitizing completely, and new applications will require pervasive compute and connectivity. On one hand, Wi-Fi 6 offers mass availability, less cost and ease of deployment. On the other, 5G is stronger in terms of handoffs, low latency and determinism.</p>
            <p>To enable the enterprise to capture new revenues, we&rsquo;re putting them in the driver&rsquo;s seat with bundles, verticals and new channels. In some places, Wi-Fi 6 makes sense. In others, 5G is the right tool for the job. Often, the enterprise will benefit from both technologies. It all comes down to the specific needs in a given vertical, the associated policy and service requirements, and how we package the services and bring them to market in new and intelligent ways. This service creation, combined with our unmatched end-to-end portfolio, makes Cisco the most important 5G vendor out there.</p>
            <p>Check out my&nbsp;<a href="https://video.cisco.com/detail/videos/service-provider/video/6106922345001/" target="_blank" rel="noopener noreferrer">presentation</a>&nbsp;from MWC Americas, where I expand on this topic and read more about the&nbsp;<a href="https://www.cisco.com/c/m/en_us/network-intelligence/service-provider/digital-transformation/5g-strategy-for-your-success.html?socialshare=lightbox-5733953067001" target="_blank" rel="noopener noreferrer">profit potential of 5G</a>.</p>',
            'title' => 'Wi-Fi 6 or 5G?',
            'author_id' => 1
        ]);

        factory(BlogPost::class)->create([
            'blog_image' => 'images/blogposts/tower-702669_640.jpg',
            'body' => '<p style="font-weight: 400;">As applications evolve in data centers and public clouds, the resulting widespread distribution of their requisite data and workloads has earned a name -- hybrid cloud. The hybrid cloud has forced a rethinking in the architecture of network resources used to connect these highly distributed environments due to complexity, security, and management concerns.</p>
            <p style="font-weight: 400;">Legacy network technologies, especially WAN, were never designed to connect application components in the cloud with on-premises databases. As a result, organizations are finding that one-size-fits-all WANs that connect multiple local area networks fail when applied to modern hybrid cloud application architectures.</p>
            <p style="font-weight: 400;">Addressing the fundamental technical incompatibilities, as well as performance and management problems common with legacy network connections, software-defined networking solutions have evolved to focus on the needs of applications -- as opposed to users.&nbsp;</p>
            <p style="font-weight: 400;">These application-driven networks provide a software-defined perimeter, enterprise-grade availability, and the flexibility of software-defined network management for distributed application components that span public, private, and on-premises environments. And unlike their predecessors, they are evolving to be deployed quickly and managed easily without the burden of proprietary hardware vendors and their antiquated delivery, support, and management models.</p>
            <p style="font-weight: 400;">The premise of an application-driven network architecture is that each application connects to a network segment with its own network architecture and security to satisfy the exact requirements of an application.&nbsp;</p>
            <p style="font-weight: 400;">An application-driven network has the ability to create custom data paths based on the needs and location of the application, as well as apply application-based access policies, Quality of Service (QoS), and security such as certificate-based authentication, packet inspection, or encryption with application-specific certificates to all traffic. All of these things combine to create an application-aware network that enhances the value and functionality of the application.&nbsp;</p>
            <p style="font-weight: 400;">The three keys of an application-driven network architecture are:&nbsp;</p>
            <p style="font-weight: 400;"><strong>1) Application-Centric&nbsp;</strong></p>
            <ul style="font-weight: 400;">
            <li>Applications, including micro-services and serverless apps, can reside anywhere on the network, which serves as connective tissue to application components distributed and managed across all network endpoints.</li>
            <li>The network can be managed as code inside of existing DevOps processes and toolsets, similar to the way infrastructure-as-a-service is now managed as code.<strong>&nbsp;</strong></li>
            </ul>
            <p style="font-weight: 400;"><strong>2) Flexible&nbsp;</strong></p>
            <ul style="font-weight: 400;">
            <li>Network agnostic functions can be overlaid on top of any network infrastructure such as ethernet, 4G, private circuits, or cloud networks.</li>
            <li>Networks can be deployed anywhere quickly, using a standard set of management and control features.</li>
            <li>Separation of data and control planes enables private point-to-point application connections to be controlled by centralized portals across all environments.</li>
            </ul>
            <p style="font-weight: 400;"><strong>3) Open&nbsp;&nbsp;</strong></p>
            <ul style="font-weight: 400;">
            <li>All network functions such as configuration, management, and monitoring are available via APIs.</li>
            <li>Open components allow for tighter integration with ecosystem partners such as Amazon, Microsoft, or Slack.&nbsp;&nbsp;</li>
            </ul>
            <p style="font-weight: 400;"><strong>So how do we take advantage of these new architectural strategies?</strong>&nbsp;</p>
            <p style="font-weight: 400;">Some workloads or datastores are not ideal for the cloud for a variety of reasons including, latency, security, regulations, and legacy system requirements. This requires them to remain localized and thus necessitates remote connectivity. These situations are most commonly found in financial services, healthcare, and industrial IoT applications, but can appear anywhere that distributed workloads and storage exist. Nearly all centralized applications have some components that could function better (lower cost, lower latency, easier compliance) outside of the public and private cloud environments where they run today.&nbsp;&nbsp;</p>
            <p style="font-weight: 400;">In these cases, application-driven networks are replacing existing WANs with public cloud integration and connections to IoT, API gateways, and edge compute resources that do not disrupt other legacy WANs or add service provider overhead.</p>
            <p style="font-weight: 400;">These solutions offer radical new approaches that integrate networking systems with application deployment and management capabilities. The optimization of application architectures that they offer is ideal for large distributed applications across multiple environments.&nbsp;</p>
            <p style="font-weight: 400;">Building an application-driven network can be accomplished using APIs available in most software-defined networking platforms. These platforms manage policy and configuration updates from a centralized controller that can be easily distributed throughout the network.&nbsp;</p>
            <p style="font-weight: 400;">Robust automated management capabilities make operating the network as simple as managing an application in the public cloud. With complexity abstracted and network management functions made available to developers and DevOps staff, applications are free to leverage network resources as needed.</p>
            <p style="font-weight: 400;">The ever-increasing volumes of workloads migrating to the public cloud present a perfect opportunity to address the challenges of hybrid cloud environments by building resilient application-centric networks.&nbsp;</p>
            <p style="font-weight: 400;">By rethinking network architectures and building networks that are dedicated to the needs of the applications they serve, organizations free themselves from traditional network constraints, resulting in performance, security, and manageability improvements.</p>',
            'title' => 'Building networks that are dedicated to the needs of the applications they serve results in performance, security, and manageability improvements.',
            'author_id' => 1
        ]);


        factory(BlogPost::class)->create([
            'blog_image' => 'images/blogposts/hexagon-3392236_1280.jpg',
            'body' => '<p style="font-weight: 400;">It&rsquo;s time for IT managers who believe Wi-Fi 6 is a far-away future for their enterprises to think again.</p>
            <p style="font-weight: 400;">With several mileposts passed of late, it now appears the road to enterprise deployment of the next-generation wireless technology has shortened considerably. Markers include the launch of Wi-Fi 6 product certification, the expansion of use beyond public sports venues, and support on new mobile devices from Apple, Google, and Samsung.</p>
            <p style="font-weight: 400;">So, what do IT managers need to know to keep current in evaluating Wi-Fi 6 for possible use in their enterprises?</p>
            <p style="font-weight: 400;"><strong>Beyond early implementors</strong></p>
            <p style="font-weight: 400;">While the spotlight has been on Wi-Fi-6 at sports venues, the latest sign of progress with the wireless technology has been its use beyond this vertical. In mid-September, Wi-Fi equipment vendor Aruba Networks claimed its access points are being used widely among educational institutions such as Seneca College and entire school districts. Healthcare boasts a growing number of implementations, as well.</p>
            <p style="font-weight: 400;">Expanded use beyond sports venues to wide use in other verticals, such as education and healthcare, demonstrates acceptance and progress across industries, which should fuel even broader deployments of the wireless networking technology.</p>
            <p style="font-weight: 400;">Wi-Fi 6 (802.11ax) is just what the doctor ordered for facilities that are relying on older versions of the tech to support super-high device density, congestion, higher throughput, and more. To date, early implementors Lucas Oil Stadium &ndash; home of the Indianapolis Colts, West Texas A&amp;M University, and pro soccer teams Houston Dynamo and Dash have invested in the latest Wi-Fi technology. All are customers of Extreme Networks.</p>
            <p style="font-weight: 400;">They&rsquo;re using Wi-Fi 6 to meet the needs of their fans who are regularly jammed into stadium venues for 3-6 hours. The resulting heavy use of Wi-Fi by these fans during games and concerts leads to continually higher data rates and peaks.</p>
            <p style="font-weight: 400;"><strong>Certified</strong></p>
            <p style="font-weight: 400;">The Wi-Fi Alliance launched its awaited Wi-Fi certification 6 program, which brings interoperability to multivendor networks comprised of a growing list of products that make up this expanding wireless ecosystem.</p>
            <p style="font-weight: 400;">The alliance claims its new Wi-Fi CERTIFIED 6 program supports a more diverse set of devices and applications, from those requiring peak performance in demanding enterprise environments to those requiring low power and low latency in smart homes or industrial IoT scenarios.</p>
            <p style="font-weight: 400;">The higher data rates, increased capacity, performance in high device-density environments and improved efficiency, provide the foundation for a host of current and emerging uses, according to the Wi-Fi Alliance.</p>
            <p style="font-weight: 400;">They can include core business applications requiring high bandwidth and low latency to staying connected and productive while moving across large, congested networks in locations such as airports and train stations. And don\'t forget streaming of ultra-high-definition video content.</p>
            <p style="font-weight: 400;">The recently launched certification program, according to the Wi-Fi Alliance, has already cleared products from vendors including Broadcom, Qualcomm, Intel, Ruckus, Samsung, Marvell, and Cypress. The industry group provides a&nbsp;<strong><a href="https://www.wi-fi.org/product-finder">product finder</a></strong>&nbsp;for interested parties.</p>
            <p style="font-weight: 400;"><strong>Survey says&hellip;</strong></p>
            <p style="font-weight: 400;">Industry association certification programs go a long way to calming IT managers who are reluctant to take the risk of becoming early implementors of a new technology. It\'s also reassuring to know that they would be part of what appears to be a broad enterprise Wi-Fi 6 adoption trend.</p>
            <p style="font-weight: 400;">In a recent cross-industry survey, two-thirds of respondents claimed they plan to deploy Wi-Fi 6 by the end of next year. Overall, 90% of participants are already planning to deploy the wireless technology.</p>
            <p style="font-weight: 400;">The survey respondents included personnel from a global cross-section of 200 enterprises, telecom service providers, and technology vendors, according to the Wireless Broadband Alliance (WBA). This is an industry group made up of service providers and technology vendors, that has been driving adoption of Wi-Fi 6 through deployment guidelines and field trials.</p>
            <p style="font-weight: 400;">The WBA says its mission is to foster interoperability between network operators, technology companies, and organizations. The survey results are part of an annual report&nbsp;<strong><a href="https://wballiance.com/resource/wba-industry-report-2020/">link</a></strong>, which the WBA claims is an in-depth analysis of the state of the Wi-Fi market.</p>
            <p style="font-weight: 400;"><strong>Mobile device support</strong></p>
            <p style="font-weight: 400;">Wi-Fi 6 received a boost with Apple&rsquo;s launch of its first compliant iPhone &ndash; the iPhone 11 - in late September, fueling strong sales of the new units here and abroad. The smartphone rolled out three units, with prices lower than its predecessors to strengthen sales. Google followed suit in mid-October with the release of the Pixel 4 smartphone, which also support Wi-Fi 6.</p>
            <p style="font-weight: 400;">This product launch adds momentum to Wi-Fi 6, months after rival Samsung launched the S10 smartphone family, which also supports the latest version of the standard. These mobile devices can be used by enterprise users beyond congested, high-density scenarios to IoT implementations.</p>
            <p style="font-weight: 400;">In further support, the&nbsp;<strong><a href="https://www.cisco.com/c/en/us/solutions/collateral/service-provider/visual-networking-index-vni/white-paper-c11-738429.html#_Toc953328">Cisco Visual Networking Index</a></strong>&nbsp;predicts by 2022, 51 percent of all IP traffic will be Wi-Fi. And the average Wi-Fi connection speed will be 54.2 Mbps. Cisco&rsquo;s VNI forecasts roughly 8.4 billion handheld or personal mobile devices by this time.</p>',
            'title' => 'The Road to Broad Wi-Fi 6 Use Shortens',
            'author_id' => 1
        ]);

        factory(BlogPost::class)->create([
            'blog_image' => 'images/blogposts/cubes-2492010_640.jpg',
            'body' => '<p>Network and application monitoring tools have always been critical for maintaining the performance of connected resources. These practices are even more important as our IT infrastructure grows more diverse; our connected devices become smarter; and our customers demand newer, better, faster services.</p>
            <p>&nbsp;</p>
            <p>There are hundreds of solutions available for monitoring network and application performance for organizations of every size. Your aggregate investment in these tools is not trivial. How can you make sure your tools deliver the highest return on your investment and have the capacity to scale up as your organization grows?</p>
            <p>&nbsp;</p>
            <p>One way is to increase the efficiency of the monitoring tools you already own and operate. The following four strategies can help you handle more volume with the tool capacity you already have.</p>
            <p>&nbsp;</p>
            <p><strong>1.</strong>&nbsp;<strong>Increase data access:&nbsp;</strong>This may seem counter-intuitive, but providing your monitoring tools with all of the data relevant to the issue you are troubleshooting can actually shorten the time it takes to arrive at a result. If you are only collecting half of the data points available, your analysis can take longer and is at greater risk of being incorrect. A more efficient approach is for you to gather all the relevant data and diagnose the issue correctly the first time, preventing the risk of recurrence.</p>
            <p>&nbsp;</p>
            <p>To ensure your tools have all the data they need, you need to collect network packets from across your entire network. The network segments most often overlooked are those where data is processed off-premises, such as in a public cloud, or where data moves only between virtual servers on the same physical host. If you use cloud-based and virtual infrastructure, you will need to deploy solutions designed specifically to access network packets in those environments.&nbsp;&nbsp;</p>
            <p>&nbsp;</p>
            <p><strong>2. Filter data before delivering to tools:&nbsp;</strong>This strategy works by reducing the volume of data packets your monitoring tools must sort through to zero-in on the ones they are meant to process. Not all tools require the same data, and it is a waste of tool capacity to process data that is irrelevant to what they do. To perform efficient data filtering, you need a fast processing engine with the ability to discern characteristics such as where the packets originate from, what applications they are associated with, their intended destination, the type of endpoint devices accessing your network, and other details.</p>
            <p>&nbsp;</p>
            <p>Products known as network packet brokers (NPBs) provide the processing power to filter data and deliver at line-rate speed to your NPM and APM solutions. You deploy NPBs between your data collection devices and your monitoring tools. This placement gives you the opportunity to control which packets you send to your tools, which tools receive the data, the speed the data arrives at your tools, and the path the data takes between the tools you connect to the packet broker. You set up these actions on an NPB using an intuitive, drag-and-drop interface that is accessible remotely and eliminates the need for programming.</p>
            <p>&nbsp;</p>
            <p>Once you have established this control layer, you have many more options for increasing the efficiency of your NPM and APM tools and extending their life span. Examples include:</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <ul>
            <li>bility to decrypt secure traffic one time and deliver the plain text to multiple tools simultaneously
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            </li>
            <li>Ability to aggregate traffic from across your network and allocate to tools based on their available capacity (load balancing)
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            </li>
            <li>Ability to collect traffic from a high-speed network segment and deliver packets at a slower speed to tools that are not upgraded
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            </li>
            </ul>
            <p><strong>3. Reduce the cost of monitoring:&nbsp;</strong>Many of the tasks performed by the NPB reduce the workload on your monitoring tools. With fewer packets to process, your tools have the capacity they need to keep up as your business grows and your traffic volume increases. Offloading work from your monitoring tools helps you control costs by delaying upgrades to your NPM and APM tools.</p>
            <p>&nbsp;</p>
            <p>You will get the most significant reduction in workload by eliminating duplicate packets from the stream of data you send to your tools. Modern networks are designed with redundancy to increase resiliency and ensure against packet loss. As a result of this practice, you will collect many duplicate packets as you monitor your network.</p>
            <p>&nbsp;</p>
            <p>A high-speed NPB can eliminate duplicate packets at very fast speeds to reduce the workload for your monitoring tools. The best packet processing engines can deduplicate at the same time they are executing your data filters, without increasing latency or dropping packets. Before you choose an NPB, make sure you test it to see exactly how it performs under pressure. The solution you choose should be able to perform all of the functions you want and not force you to choose between one or the other.</p>
            <p>&nbsp;</p>
            <p><strong>4. Monitor at the network edge:&nbsp;</strong>In many organizations, critical transactions and operations are shifting to the edge of the network&mdash;taking place at remote endpoints or branch offices. Research conducted by Enterprise Management Associates found a steady uptick in the number of remote sites connecting to wide-area networks and the number of devices connecting to the network at those sites.&nbsp;The result is that data which is critical to NPM and APM is farther away from the data center where they are typically deployed.</p>
            <p>&nbsp;</p>
            <p>Many enterprises are choosing to monitor at least partially at the network edge. This strategy allows them to maintain performance and user experience without incurring the latency and cost of transporting data back to the data center. As in the data center, the best way to keep NPM and APM solutions working efficiently is is to establish a data control layer with an NPB. You may not need all of the processing power of a data center-based NPB, but the ability to filter and condense data will increase the efficiency of your edge-located tools. Look for NPBs that are sized and priced appropriately for edge deployments.</p>
            <p>&nbsp;</p>
            <p>Once you are monitoring on the network edge, you can use the processing power of the existing tools in your cloud or data center to conduct deeper analysis. A new trend is for companies to use data center tools to analyze simulation algorithms and create optimization strategies for data collection and analysis on the edge. The merging of edge monitoring with centralized analytics can support significant improvements in operational efficiency and customer experience.</p>',
            'title' => 'Four Strategies to Help your NPM and APM Tools Work More Efficiently',
            'author_id' => 1
        ]);


        factory(BlogPost::class)->create([
            'blog_image' => 'images/blogposts/iot_internet-of-things_chains_security_by-mf3d-getty-100799692-large.jpg',
            'body' => '<h3>Deploying microsegmentation as part of a broad IoT security strategy can enable more granular control of network systems and better isolation if a security flaw is exploited.</h3>
            <p>The Internet of Things (<a href="https://www.networkworld.com/article/3207535/what-is-iot-how-the-internet-of-things-works.html">IoT</a>) promises some big benefits for organizations, such as greater insights about the performance of corporate assets and finished products, improved manufacturing processes, and better customer services. The nagging security issues related to IoT, unfortunately, remain a huge concern for companies and in some cases might be keeping them from moving forward with initiatives. One possible solution to at least some of the security risks of IoT is&nbsp;<a href="https://www.networkworld.com/article/3247672/what-is-microsegmentation-how-getting-granular-improves-network-security.html">microsegmentation</a>, a&nbsp; concept in networking that experts say could help keep IoT environments under control.</p>
            <p>With microsegmentation, organizations create secure zones within their data centers and cloud environments that enable them to isolate workloads from each other and secure them individually. In IoT environments, microsegmentation can give companies greater control over the growing amount of lateral communication that occurs between devices, bypassing perimeter-focused security tools.</p>
            <section class="bodee">
            <div id="drr-container" class="is-insider">
            <p>It might still be early in the game for companies to be&nbsp;<a href="https://www.networkworld.com/article/3437956/to-secure-industrial-iot-use-segmentation-instead-of-firewalls.html">using microsegmentation for IoT</a>, but industry watchers see potential for IoT deployments to spur enterprises to adopt microsegmentation for more granular, less complex protection than traditional firewalls can provide.</p>
            <div class="connatix">
            <div id="cnx-adUnit-overlay">&nbsp;</div>
            <div id="cnx-video-image">
            <div class="cnx-ratio">&nbsp;</div>
            <div class="cnx-video-content"><img src="https://i.connatix.com/s3/connatix-uploads/73084f78-9eb7-48cc-a3e1-a5a992c01d6f/1.jpg?mode=stretch&amp;connatiximg=true&amp;scale=both&amp;height=225&amp;width=400" />
            <div id="2ca89bcf7a27350773711575710837105"><video class="" crossorigin="anonymous" muted="" width="300" height="150" data-mce-fragment="1"></video>
            <div class="resize-triggers">&nbsp;</div>
            </div>
            <div class="resize-triggers">&nbsp;</div>
            </div>
            </div>
            </div>
            <h2>IoT introduces new security risks</h2>
            <p><a href="https://www.networkworld.com/article/3332032/top-10-iot-vulnerabilities.html">IoT security risks</a>&nbsp;can include any number of threats involving the connected devices themselves, the software that supports IoT, and the networks that make all the connections possible.</p>
            <p>As IoT deployments have grown, so have threats to security. There&rsquo;s been a &ldquo;dramatic&rdquo; increase in IoT-related data breaches since 2017, according to a report from research firm&nbsp;<a href="https://www.ponemon.org/" rel="nofollow">Ponemon Institute</a>&nbsp;and risk management services firm&nbsp;<a href="https://www.santa-fe-group.com/" rel="nofollow">The Santa Fe Group</a>. Further complicating the issue, most organizations are not aware of every insecure IoT device or application in their environment or from third party vendors. Ponemon&rsquo;s research shows that many organizations have no centralized accountability to address or manage IoT risks, and a majority think their data will be breached over the next 24 months.</p>
            <p>IoT security risks can be particularly high for industries such as healthcare, because of the high volumes of sensitive information being gathered and shared by devices over networks. Among 232 healthcare organizations surveyed by research firm&nbsp;<a href="https://www.vansonbourne.com/" rel="nofollow">Vanson Bourne</a>, 82% had experienced an IoT-focused cyber attack in the past year. When asked to identify where the most prominent vulnerabilities exist within healthcare organizations, networks were cited most frequently (50%), followed by mobile devices and accompanying apps (45%), and IoT devices (42%).</p>
            <p><strong>READ MORE:</strong>&nbsp;<a href="https://www.networkworld.com/article/3265065/penn-state-secures-building-automation-iot-traffic-with-microsegmentation.html">Penn State secures building automation, IoT traffic with microsegmentation</a></p>
            <h2>How microsegmentation helps IoT security</h2>
            <p><a href="https://www.networkworld.com/article/3247672/what-is-microsegmentation-how-getting-granular-improves-network-security.html">Microsegmentation</a>&nbsp;is designed to make network security more granular.&nbsp;Other solutions such as next-generation firewalls, virtual local area networks (VLAN), and access control lists (ACL) provide some level of network segmentation. But with microsegmentation, policies are applied to individual workloads in order to provide better protection against attacks. As a result, these tools provide more fine-grained segmentation of traffic than offerings such as VLANs.</p>
            <p>What&rsquo;s helped advance the development of microsegmentation is the emergence of software-defined networks (<a href="https://www.networkworld.com/article/3209131/what-sdn-is-and-where-its-going.html">SDN</a>) and network virtualization. By using software that&rsquo;s decoupled from network hardware, segmentation is easier to implement than if the software were not decoupled from the underlying hardware.</p>
            <p>Because microsegmentation provides greater control over traffic in data centers than perimeter-focused products such as firewalls, it can stop attackers from gaining entry into networks to do damage</p>
            </div>
            </section>',
            'title' => 'Can microsegmentation help IoT security?',
            'author_id' => 1
        ]);

        factory(BlogPost::class)->create([
            'blog_image' => 'images/blogposts/analytics-3088958_640.jpg',
            'body' => '<p>Testing or calibrating your software tools is critical when understanding how they behave and a great application baselining practice.</p>
            <p style="font-weight: 400;">When using any tool, it is critical to understand how it works.</p>
            <p style="font-weight: 400;">This is especially important when using Microsoft-based tools. These tools may have their own drivers, which in turn need to work with Microsoft NDIS drivers, operating system, and of course, the application. Every one of these components will have its limitations, which will affect the performance or results that tool reports.</p>
            <p style="font-weight: 400;">A great example is the Microsoft&nbsp;<strong><a href="https://docs.microsoft.com/en-us/windows-server/administration/windows-commands/ping">ping command</a></strong>. This widely used utility will send a ping packet every second. That rate is not adjustable. If you need to ping something more frequently than once a second, look into&nbsp;<strong><a href="https://www.networkcomputing.com/networking/(https:/www.cfos.de/en/ping/ping.htm">hrPing</a></strong>. If you run hrPing and ping side-by-side, you will see a difference in the results reported because they are different applications with different default options.</p>
            <p style="font-weight: 400;">The concern I have is that most people will download a utility and blindly believe whatever it reports without spending a few minutes trying to understand how it works.</p>
            <p style="font-weight: 400;">In this article, I cover Nping, a utility that I am seeing more and more in the field. Nping is part of the&nbsp;<strong><a href="https://nmap.org/nping/">Nmap set of tools</a></strong>, which is available in Linux, Mac OS, and Windows.</p>
            <p style="font-weight: 400;">The purpose of the article is to demonstrate that on my Windows system, with the options used, Nping seemed to max out at over 7,000 packets per second. You can expect to generate less throughput when using smaller payloads.&nbsp;The good news is that knowing this, you can generate varying levels of throughput. However, you need to test your system.&nbsp;</p>
            <p style="font-weight: 400;">In my example, I used my Optiview to measure my results. A good alternative is to use a managed switch and monitor the port statistics as you generate traffic. I know my Optiview could easily handle 100mbps (and I have one), which is why I chose it.</p>
            <p style="font-weight: 400;">Testing or calibrating your software tools is critical when understanding how they behave and a great application baselining practice.</p>',
            'title' => 'Exploring Nping Performance Issues on a Windows Computer',
            'author_id' => 1
        ]);

        factory(BlogPost::class)->create([
            'blog_image' => 'images/blogposts/cloud-computing-3385323_640.jpg',
            'body' => '<p>Companies are moving further into the cloud and undergoing data modernization. What some dont realize yet is that one strategy is better than two.</p>
            <p>Global professional services firm&nbsp;Deloitte&nbsp;recently published a&nbsp;report&nbsp;asking whether data modernization is driving cloud adoption or vice versa. Deloitte defines "data modernization" as moving from legacy databases to modern databases that can handle unstructured data.</p>
            <p>According to the report, "data modernization efforts offer substantial cost savings over previously used data management strategies." A lot of the cost savings come from cloud databases, which provide the usual cloud benefits plus lower database-related overhead.</p>
            <p>As Deloitte points out, cloud providers offer a lot more than storage and compute these days, they also offer modern databases and analytics capabilities that take advantage of AI. While data warehouses arent going away anytime soon, businesses are doing more in the cloud than ever and the trend will continue.</p>
            <p>"This year, more respondents placed a higher premium on AI to drive business results, specifically with AI automation," said Chris Jackson, private company technology leader at Deloitte. "Predictive analytics and legacy modernization are two of the top investment priorities. The companies using predictive analytics to forecast business results or customer behavior has jumped 65% percent over the last five years."</p>',
            'title' => 'Your Cloud and Data Management Strategies are About to Collide',
            'author_id' => 1
        ]);
    }
}
