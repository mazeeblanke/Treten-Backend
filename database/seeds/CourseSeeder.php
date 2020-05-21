<?php

use App\Course;
use App\CoursePath;
use App\CourseCategory;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // COURSE PATHS
        $rsPath = CoursePath::whereName('Cisco R&S')->first();
        $securityPath = CoursePath::whereName('Cisco Security')->first();
        $serviceProviderPath = CoursePath::whereName('Cisco Service Provider')->first();

        // COURSE CATEGORIES
        $associate = CourseCategory::whereName('associate')->first();
        $professional = CourseCategory::whereName('professional')->first();
        $expert = CourseCategory::whereName('expert')->first();

        $ccnaRS = factory(Course::class)->create([
            'title' => 'CCNA R&S',
            'description' => 'Almost every organisation in world today have a networking infrastructure and so naturally, every one of these also need networkers. With more & more businesses going online, e-commerce becoming the trend and digital awareness taking over, we need more and more workforce to control operations in the background. If you have already completed graduation and just waiting to be part of the I.T conglomerates, there are numerous profiles waiting for you depending on your experience level. Here are few job roles a CCNA helps you get.

            Network Administration: Your CCNA opens the door to this very desirous position in industry. However good things comes with time and experience & just your CCNA will not make you a network admin. With some years of experience in the networking industry most networkers end up in admin role which is a high paying job. System Administration & system support. CCNA also help you with system administrator profile. If you checkout job portals for system admin roles you can see most if them mention CCNA as a requisite.

            Server Administration & Server Support: For those who want to be able to work on server side

            Freelance Job Roles: If you have more creative ambitions, you can also start your own Youtube channel and earn money by posting networking videos online. A website or blog dedicated to networking can also be fascinating to some with flair for writing. As you gain enough experience and financial resources, you can give shape to more ambitious entrepreneurial dreams like your own network training school..',
            'price' => 50000,
            'is_published' => 1,
            'duration' => 40,
            'author_id' => 1,
            'course_path_id' => $rsPath->id,
            'course_path_position' => 1,
        ]);
        $ccnaRS->categories()->attach([$associate->id]);


        $ccieRS = factory(Course::class)->create([
            'title' => 'CCIE R&S',
            'description' => 'Almost every organisation in world today have a networking infrastructure and so naturally, every one of these also need networkers. With more & more businesses going online, e-commerce becoming the trend and digital awareness taking over, we need more and more workforce to control operations in the background. If you have already completed graduation and just waiting to be part of the I.T conglomerates, there are numerous profiles waiting for you depending on your experience level. Here are few job roles a CCIE helps you get.

            Network Administration: Your CCIE opens the door to this very desirous position in industry. However good things comes with time and experience & just your CCIE will not make you a network admin. With some years of experience in the networking industry most networkers end up in admin role which is a high paying job. System Administration & system support. CCNA also help you with system administrator profile. If you checkout job portals for system admin roles you can see most if them mention CCIE as a requisite.

            Server Administration & Server Support: For those who want to be able to work on server side

            Freelance Job Roles: If you have more creative ambitions, you can also start your own Youtube channel and earn money by posting networking videos online. A website or blog dedicated to networking can also be fascinating to some with flair for writing. As you gain enough experience and financial resources, you can give shape to more ambitious entrepreneurial dreams like your own network training school..',
            'price' => 100000,
            'is_published' => 1,
            'duration' => 140,
            'author_id' => 1,
            'course_path_id' => $rsPath->id,
            'course_path_position' => 3,
            'faqs' => '[{"question":"HOW DO I REGISTER FOR A TRAINING?","answer":"Simply preview the course and click on the ENROLL button"}]'
        ]);
        $ccieRS->categories()->attach([$expert->id]);


        $ccnaSecurity = factory(Course::class)->create([
            'title' => 'CCNA Security',
            'description' => 'Cisco Certified Network Associate Security (CCNA Security) validates associate-level knowledge and skills required to secure Cisco networks. With a CCNA Security certification, a network professional demonstrates the skills required to develop a security infrastructure, recognize threats and vulnerabilities to networks, and mitigate security threats. The CCNA Security curriculum emphasizes core security technologies, the installation, troubleshooting and monitoring of network devices to maintain integrity, confidentiality and availability of data and devices, and competency in the technologies that Cisco uses in its security structure.',
            'price' => 30000,
            'is_published' => 1,
            'duration' => 20,
            'author_id' => 1,
            'course_path_id' => $securityPath->id,
            'course_path_position' => 1,
        ]);
        $ccnaSecurity->categories()->attach([$associate->id]);


        $ccieSecurity = factory(Course::class)->create([
            'title' => 'CCIE Security',
            'description' => 'Cisco Certified Internetwork Expert Security (CCIE Security) validates associate-level knowledge and skills required to secure Cisco networks. With a CCIE Security certification, a network professional demonstrates the skills required to develop a security infrastructure, recognize threats and vulnerabilities to networks, and mitigate security threats. The CCNA Security curriculum emphasizes core security technologies, the installation, troubleshooting and monitoring of network devices to maintain integrity, confidentiality and availability of data and devices, and competency in the technologies that Cisco uses in its security structure.',
            'price' => 230000,
            'is_published' => 1,
            'duration' => 220,
            'author_id' => 1,
            'course_path_id' => $securityPath->id,
            'course_path_position' => 3,
            'faqs' => '[{"question":"WHAT DO I DO IF I CANNOT FIND A MATCHING COURSE SCHEDULE?","answer":"Give us a call."}]'
        ]);
        $ccieSecurity->categories()->attach([$expert->id]);


        $ccnaServiceProvider = factory(Course::class)->create([
            'title' => 'CCNA Service provider',
            'description' => '
                <p>The CCNA Service Provider certification is for service provider network engineers, technicians, and support personnel who want to configure and implement robust baseline Cisco Service Provider IP next-generation networks.</p>
                <p>
                Cisco Certified Network Associate Service Provider (CCNA SP) certification is for service provider network engineers, technicians and designers who focus on the latest in Service Provider industry core networking technologies and trends. With the ability to configure, implement, and troubleshoot baseline Cisco Service Provider Next-Generation networks, CCNA SP certified individuals deploy, maintain and improve carrier-grade network infrastructures..
                </P>
            ',
            'price' => 40000,
            'is_published' => 1,
            'duration' => 23,
            'author_id' => 1,
            'course_path_id' => $serviceProviderPath->id,
            'course_path_position' => 1,
        ]);
        $ccnaServiceProvider->categories()->attach([$associate->id]);

        $ccieServiceProvider = factory(Course::class)->create([
            'title' => 'CCIE Service Provider',
            'description' => '
                <p>The CCIE Service Provider certification is for service provider network engineers, technicians, and support personnel who want to configure and implement robust baseline Cisco Service Provider IP next-generation networks.</p>
                <p>
                Cisco Certified Internetwork Expert Service Provider (CCIE SP) certification is for service provider network engineers, technicians and designers who focus on the latest in Service Provider industry core networking technologies and trends. With the ability to configure, implement, and troubleshoot baseline Cisco Service Provider Next-Generation networks, CCIE SP certified individuals deploy, maintain and improve carrier-grade network infrastructures..
                </P>
            ',
            'price' => 60000,
            'is_published' => 1,
            'duration' => 83,
            'author_id' => 1,
            'course_path_id' => $serviceProviderPath->id,
            'course_path_position' => 3,
        ]);
        $ccieServiceProvider->categories()->attach([$expert->id]);


        $ccnaCyberOps = factory(Course::class)->create([
            'title' => 'CCNA Cyber Ops',
            'description' => '
                <p>
                The CCNA Cyber Ops Certification is the first step in acquiring skills and equipping oneself to work with the SOC teams. The online security of network from cyber attackers has very great importance. The IT, as well as non-IT industries in the modern era, are exposed to online threats. As we continuously face Cyber Security breaches throughout the world, there is a huge need for effectively responding to Security incidents. Here comes CCNA Cyber Ops Certifications that will prepare you for a Career in Cyber Security analysis responding to the threats. An estimated need exists for 2 Million Cybersecurity professionals by 2019 in which Cisco Certified engineers will have an important role to impart. The Certifications helps Security personnel get equipped with the ability to monitor security systems and detect online attacks.
                </p>
                <p>
                Cybersecurity Operations jobs play a key role in monitoring, detecting, investigating, analyzing and responding to events, thus protecting systems against threats and vulnerabilities. This is the fastest-growing roles in the IT industry. This course is aligned with the Understanding Cisco cybersecurity Fundamentals exam (210-250 SECFND) and Implementing Cisco Cybersecurity Operations exam (210-225 SECOPS). The CCNA Cybersecurity Operations curriculum provides an introduction to the knowledge and skills needed for a Security Analyst working with a Security Operations Center team. It teaches core security skills needed for monitoring, detecting, investigating, analyzing and responding to security events, thus protecting systems and organizations from cybersecurity risks, threats, and vulnerabilities. By the end of the course, students will be prepared to: Understand cybersecurity operations network principles, roles, and responsibilities as well as the related technologies, tools, regulations, and frameworks available. Apply knowledge and skills to monitor, detect, investigate, analyze and respond to security incidents. Apply for entry-level jobs as Associate Security Analyst and Incident Responder. Take the Cisco CCNA Cybersecurity Operations Certification exam. The CCNA Cyber Ops certification prepares candidates to begin a career working with associate-level cybersecurity analysts within security operations centers. The students will learn how to detect and respond to cybersecurity threats...
                </P>
            ',
            'price' => 35000,
            'is_published' => 1,
            'duration' => 23,
            'author_id' => 1,
            'course_path_id' => null,
            'course_path_position' => null,
            'faqs' => '[{"question":"Can I just enroll in for a single course? I am not interested in the entire bundle.","answer":"To enroll in an individual course, search for the course title in the search bar. and view the course and click on the enroll button"}]'
        ]);
        $ccnaCyberOps->categories()->attach([$associate->id]);


        $ccnpRS = factory(Course::class)->create([
            'title' => 'Cisco Devnet Professional Core(350-901)',
            'description' => '
                <p>
                Cisco Certified Network Professional (CCNP) Routing and Switching certification validates the ability to plan, implement, verify and troubleshoot local and wide-area enterprise networks and work collaboratively with specialists on advanced security, voice, wireless and video solutions. The CCNP Routing and Switching certification is appropriate for those with at least one year of networking experience who are ready to advance their skills and work independently on complex network solutions. Those who achieve CCNP Routing and Switching have demonstrated the skills required in enterprise roles such as network engineer, support engineer, systems engineer or network technician. The routing and switching protocol knowledge from this certification will provide a lasting foundation as these skills are equally relevant in the physical networks of today and the virtualized network functions of tomorrow
                </p>
                <p>
                In this generation of rapid growth in IT industries and a lot of eligible candidates for the jobs, the unemployment rate keeps on increasing. Companies seek candidates who are capable of doing work without much need for training. So for the entry-level jobs, it is vital to have some initiatives for that.In our ccna online course, you will learn the basics and advance level of networking, routing and switching. You will be able to become an associate with any good networking companies and with CCNP certificate you will be a networking professional.You will not only learn the fundamentals of networking, routing and switching, also ability to plan, implement and troubleshoot LAN and WAN of the enterprise. You will also work with network specialists on security, voice, wireless and video solutions..
                </P>
            ',
            'price' => 35000,
            'is_published' => 1,
            'duration' => 23,
            'author_id' => 1,
            'course_path_id' => $rsPath->id,
            'course_path_position' => 2,
            'faqs' => '[{"question":"WHAT DO I HAVE TO BRING FOR THE TRAINING?","answer":"You would need laptop. With minimum 6gb of ram."},{"question":"WHAT IS THE MAXIMUM NUMBER OF PARTICIPANTS FOR THE TRAINING COURSES?","answer":"Maximum in a class is 10. We try as much as possible, not to crowd our classrooms."},{"question":"CAN I ATTEND A COURSE OUTSIDE OF NIGERIA","answer":"Yes, you most definitely can. We have prepared our remote live classes just for such purposes"}]'
        ]);
        $ccnpRS->categories()->attach([$professional->id]);


        $ccnpSecurity = factory(Course::class)->create([
            'title' => 'CCNP Enterprise Core(350-401)',
            'description' => '
                <p>
                Cisco Certified Network Professional (CCNP) Routing and Switching certification validates the ability to plan, implement, verify and troubleshoot local and wide-area enterprise networks and work collaboratively with specialists on advanced security, voice, wireless and video solutions. The CCNP Routing and Switching certification is appropriate for those with at least one year of networking experience who are ready to advance their skills and work independently on complex network solutions. Those who achieve CCNP Routing and Switching have demonstrated the skills required in enterprise roles such as network engineer, support engineer, systems engineer or network technician. The routing and switching protocol knowledge from this certification will provide a lasting foundation as these skills are equally relevant in the physical networks of today and the virtualized network functions of tomorrow
                </p>
                <p>
                In this generation of rapid growth in IT industries and a lot of eligible candidates for the jobs, the unemployment rate keeps on increasing. Companies seek candidates who are capable of doing work without much need for training. So for the entry-level jobs, it is vital to have some initiatives for that.In our ccna online course, you will learn the basics and advance level of networking, routing and switching. You will be able to become an associate with any good networking companies and with CCNP certificate you will be a networking professional.You will not only learn the fundamentals of networking, routing and switching, also ability to plan, implement and troubleshoot LAN and WAN of the enterprise. You will also work with network specialists on security, voice, wireless and video solutions..
                </P>
            ',
            'price' => 35000,
            'is_published' => 1,
            'duration' => 23,
            'author_id' => 1,
            'course_path_id' => $securityPath->id,
            'course_path_position' => 2,
            'faqs' => '[{"question":"Can I just enroll in for a single course? I am not interested in the entire bundle.","answer":"To enroll in an individual course, search for the course title in the search bar. and view the course and click on the enroll button"}]'
        ]);
        $ccnpSecurity->categories()->attach([$professional->id]);


        $ccnpServiceProvider = factory(Course::class)->create([
            'title' => 'Cisco Certified CyberOps Associate',
            'description' => '
                <p>The CCNP Service Provider certification is for service provider network engineers, technicians, and support personnel who want to configure and implement robust baseline Cisco Service Provider IP next-generation networks.</p>
                <p>
                Cisco Certified Network Professional Service Provider (CCNP SP) certification is for service provider network engineers, technicians and designers who focus on the latest in Service Provider industry core networking technologies and trends. With the ability to configure, implement, and troubleshoot baseline Cisco Service Provider Next-Generation networks, CCNA SP certified individuals deploy, maintain and improve carrier-grade network infrastructures..
                </P>
            ',
            'price' => 55000,
            'is_published' => 1,
            'duration' => 23,
            'author_id' => 1,
            'course_path_id' => $serviceProviderPath->id,
            'course_path_position' => 2,
            'faqs' => '[{"question":"Can I just enroll in for a single course? I am not interested in the entire bundle.","answer":"To enroll in an individual course, search for the course title in the search bar. and view the course and click on the enroll button"}]'
        ]);
        $ccnpServiceProvider->categories()->attach([$professional->id]);



    }
}
