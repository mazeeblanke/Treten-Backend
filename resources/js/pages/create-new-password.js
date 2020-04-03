import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { Tabs, Button, Input, Form } from 'antd';
import withAuthLayout from '../layouts/withAuthLayout';
import ReactDOM from 'react-dom';
import queryString from 'query-string'
import notifier from 'simple-react-notifier';

const { TabPane } = Tabs;

class PasswordReset extends Component {
  constructor(props) {
    super(props);
    this.createNewPassword = this.createNewPassword.bind(this)
    this.state = {
      isCreatingNewPassword: false
    }
  }

  createNewPassword(e) {
    e.preventDefault();
    this.setState({
      isCreatingNewPassword: true
    })

    this.props.form.validateFields((err, form) => {
      if (!err) {
        let qs = queryString.parseUrl(location.href);
        axios.post('/t/api/password/reset', {
          ...form,
          password_confirmation: form.password,
          email: qs.query.email,
          token: location.href.substring(location.href.lastIndexOf('/') + 1, location.href.lastIndexOf('?'))
        }).then((res) => {
          this.setState({
            isCreatingNewPassword: false
          });
          notifier.success(res.data.message)
          setTimeout(() => {
              window.location = '/';
          }, 2000)
        }).catch((err) => {
          let error;
          if (err.response.data.email) {
            error = err.response.data.email;
          }

          if (err.response.data.errors) {
            if (err.response.data.errors.email instanceof Array)
            {
              error = err.response.data.errors.email[0];
            }

            if (err.response.data.errors.password instanceof Array)
            {
              error = err.response.data.errors.password[0];
            }
          }
          notifier.error('ERROR! '+error)
          this.setState({
            isCreatingNewPassword: false
          })
        })
      } else {
        this.setState({
          isCreatingNewPassword: false
        })
      }
    });

  }

  render() {
    const { getFieldDecorator } = this.props.form;
    return (
      <section className="auth has-grey-bg has-min-full-vh pb-5">
        <div className="container pt-5 has-full-height">
          <div className="row justify-content-center has-full-height">
            <div className="col-md-5 auth__wrapper">
              <div className="auth__container">
                <Tabs className="has-full-height resetpassword " type="card">
                  <TabPane className="has-full-height pr-7 pl-7" tab="Reset password" key="register">
                    <p className="register__sub-text text-center mt-3">
                      Create a new password for your account
                    </p>


                    <Form onSubmit={this.createNewPassword} className="login-form">
                      <Form.Item>
                        <label htmlFor="password">
                          Password
                        </label>
                        {getFieldDecorator('password', {
                          rules: [{ required: true, message: 'Please input your Password!' }],
                        })(
                          <Input.Password
                            type="password"
                            placeholder="Enter your password"
                          />,
                        )}
                      </Form.Item>

                      <Form.Item className="is-full-width">
                        <Button
                          disabled={this.state.isCreatingNewPassword}
                          loading={this.state.isCreatingNewPassword}
                          type="primary"
                          htmlType="submit"
                          className="auth__btn">
                          Create new password
                        </Button>
                      </Form.Item>
                    </Form>
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

PasswordReset.propTypes = {

};

const WrappedPasswordResetForm = Form.create({ name: 'login' })(PasswordReset);

// export default withAuthLayout(WrappedPasswordResetForm);

PasswordReset = withAuthLayout(WrappedPasswordResetForm);

if (document.getElementById('auth')) {
  ReactDOM.render(<PasswordReset />, document.getElementById('auth'));
}
