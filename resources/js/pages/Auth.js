import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { Tabs, Button } from 'antd';
import withAuthLayout from '../layouts/withAuthLayout';
import RegisterForm from '../components/auth/RegisterForm';
import LoginForm from '../components/auth/LoginForm';
import ReactDOM from 'react-dom';
import queryString from 'query-string';

const { TabPane } = Tabs;

class Auth extends Component {
  constructor(props) {
    super(props);
  }

  render() {
    const parsed = queryString.parse(location.search)
    const redirect = (parsed || {}).redirect
    return (
      <section className="auth has-grey-bg has-min-full-vh pb-5">
        <div className="container pt-5 has-full-height">
          <div className="row justify-content-center has-full-height">
            <div className="col-md-5 auth__wrapper">
              <div className="auth__container">
                <Tabs className="has-full-height" type="card">
                  <TabPane className="has-full-height register pr-7 pl-7" tab="Register" key="register">
                    <h4 className="register__main-text text-center mt-2">Register to get your student account</h4>
                    <p className="register__sub-text text-center">
                      Your student account is your portal to all things:
                      your classroom, projects, forums, career resources, and more!
                    </p>

                    <p className="mt-4 is-grey-light divider">
                      Continue with
                    </p>

                    <div className="row justify-content-md-between">
                      <div className="col-md-6 mb-3">
                        <Button
                          href={`auth/linkedin${location.search}`}
                          type="primary"
                          htmlType="submit"
                          className="linkedin-btn has-full-width">
                          <img className="mr-2" src="/t/images/social/linkedin icon.png" />
                          LinkedIn
                        </Button>
                      </div>
                      <div className="col-md-6 mb-3">
                        <Button
                          href={`auth/facebook${location.search}`}
                          type="primary"
                          htmlType="submit"
                          className="facebook-btn has-full-width">
                            <img className="mr-2" src="/t/images/social/facebook icon.png" />
                          Facbook
                        </Button>
                      </div>
                    </div>
                    <p className="is-grey-light divider">Or</p>
                    <RegisterForm />
                    <p className="auth__note">
                      By registering, you agree to our Terms and Conditions and Policies
                    </p>
                  </TabPane>


                  <TabPane className="has-full-height login  pr-7 pl-7" tab="Login" key="login">
                    <h4 className="register__main-text text-center mt-2">Welcome back. Log in to your account</h4>
                    <p className="register__sub-text text-center">
                      Your student account is your portal to all things: your classroom,
                      projects, forums, career resources, and more!
                    </p>
                    <p className="mt-4 is-grey-light divider">
                      Continue with
                    </p>

                    <div className="row justify-content-md-between">
                      <div className="col-sm-6">
                        <Button
                            href="auth/linkedin"
                            type="primary"
                            htmlType="submit"
                            className="linkedin-btn mb-4 has-full-width">
                            <img className="mr-2" src="/t/images/social/linkedin icon.png" />
                            LinkedIn
                        </Button>
                      </div>
                      <div className="col-sm-6">
                        <Button
                            href="auth/facebook"
                            type="primary"
                            htmlType="submit"
                            className="facebook-btn  mb-4 has-full-width">
                            <img className="mr-2" src="/t/images/social/facebook icon.png" />
                            Facbook
                        </Button>
                      </div>



                    </div>

                    <p className="is-grey-light divider">Or</p>
                    <LoginForm />
                  </TabPane>


                </Tabs>
              </div>
            </div>
          </div>
        </div>
      </section>
    );
  }
}

Auth.propTypes = {

};

Auth = withAuthLayout(Auth);

if (document.getElementById('auth')) {
    ReactDOM.render(<Auth />, document.getElementById('auth'));
}
