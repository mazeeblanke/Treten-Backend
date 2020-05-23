import React, { Component } from 'react';
import PropTypes from 'prop-types';
import Link from 'next/link'
import Brand from './Brand';

class Footer extends Component {
  render() {
    return (
      <section className="footer has-dark-bg pt-7 pb-7">
        <div className="container">
          <div className="row">
            <div className="col-sm-12 col-md-3">
              <Brand isWhite/>
              <address className="mt-3 mb-1">
                <p className="is-smokewhite font-weight-light p-0 m-0">Oak Place Plot 14, Nike Art Gallery Road,</p>
                <p className="is-smokewhite font-weight-light p-0 m-0">Ikate, Lekki Phase 1,</p>
                <p className="is-smokewhite font-weight-light p-0 m-0">Lagos, Nigeria.</p>
              </address>

              <p className="m-0 is-smokewhite">info@tretennetworks.com</p>

              <a className="is-smokewhite m-0" href="tel:(+234) 9060 0063 12">(+234) 9060 0063 12</a>

              <div className="mt-2">
                {/* <div className="col-md-1"> */}
                  <img alt="linkedin social icon" className="mr-2" src="images/social/linkedin icon.png" />
                {/* </div> */}
                {/* <div className="col-md-1"> */}
                  <img alt="facebook social icon" className="mr-2" src="images/social/facebook icon.png" />
                {/* </div> */}
                {/* <div className="col-md-1"> */}
                  <img alt="twitter social icon" className="mr-2" src="images/social/twitter icon.png" />
                {/* </div> */}
                {/* <div className="col-md-1"> */}
                  <img alt="youtube social icon" className="mr-2" src="images/social/youtube icon.png" />
                {/* </div> */}
                {/* <div className="col-md-1"> */}
                  <img alt="instagram social icon" className="mr-2" src="images/social/instagram icon.png" />
                {/* </div> */}
              </div>

              <p className="mt-4 is-smokewhite font-weight-light">2018 Treten Academy. All Rights Reserved</p>

            </div>
            <div className="col-sm-12 col-md-3">
              <h6 className="is-white">Company</h6>
              <ul className="p-0">
                <li>
                  <Link href="/about-us">
                    <a className="is-smokewhite font-weight-light">About us</a>
                  </Link>
                </li>
                <li>
                  <Link href="/why-us">
                    <a className="is-smokewhite font-weight-light">Why us</a>
                  </Link>
                </li>
                <li>
                  <Link href="/instructors">
                    <a className="is-smokewhite font-weight-light">Instructors</a>
                  </Link>
                </li>
                <li>
                  <Link href="/testimonials">
                    <a className="is-smokewhite font-weight-light">Testimonials</a>
                  </Link>
                </li>
                <li>
                  <Link href="/gallery">
                    <a className="is-smokewhite font-weight-light">Gallery</a>
                  </Link>
                </li>
                <li>
                  <Link href="/become-an-instructor">
                    <a className="is-smokewhite font-weight-light">Become an instructor</a>
                  </Link>
                </li>
              </ul>
            </div>
            <div className="col-sm-12 col-md-3">
              <h6 className="is-white">Support</h6>
              <ul className="p-0">
                <li>
                  <Link href="/contact-us">
                    <a className="is-smokewhite font-weight-light">Contact</a>
                  </Link>
                </li>
                <li>
                  <Link href="/termsandconditions">
                    <a className="is-smokewhite font-weight-light">Terms and conditions</a>
                  </Link>
                </li>
                <li>
                  <Link href="/policies">
                    <a className="is-smokewhite font-weight-light">Policies</a>
                  </Link>
                </li>
                <li>
                  <Link href="/resources/interview-questions">
                    <a className="is-smokewhite font-weight-light">Resources</a>
                  </Link>
                </li>
              </ul>
            </div>
            <div className="col-sm-12 col-md-3">
              <h6 className="is-white">Popular courses</h6>
              <ul className="p-0">
                <li>
                  <Link href="/ccna-service-provider">
                    <a className="is-smokewhite font-weight-light">CCNA Service Provider</a>
                  </Link>
                </li>
                <li>
                  <Link href="/ccna-cyber-ops">
                    <a className="is-smokewhite font-weight-light">CCNA Cyber Ops</a>
                  </Link>
                </li>
                <li>
                  <Link href="/ccnp-security">
                    <a className="is-smokewhite font-weight-light">CCNP Security</a>
                  </Link>
                </li>
                <li>
                  <Link href="/ccie-service-provider">
                    <a className="is-smokewhite font-weight-light">CCIE Service Provider</a>
                  </Link>
                </li>
                <li>
                  <Link href="/ccnp-rs">
                    <a className="is-smokewhite font-weight-light">CCNP R&S</a>
                  </Link>
                </li>
                <li>
                  <Link href="/ccie security">
                    <a className="is-smokewhite font-weight-light">CCIE Security</a>
                  </Link>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </section>
    );
  }
}

Footer.propTypes = {

};

export default Footer;