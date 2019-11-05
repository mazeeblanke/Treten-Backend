import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { Tabs, Button, Input, Form, message } from 'antd';
import withAuthLayout from '../layouts/withAuthLayout';
import ReactDOM from 'react-dom';

const { TabPane } = Tabs;

class PasswordReset extends Component {
  constructor(props) {
    super(props);

    this.sendResetLink = this.sendResetLink.bind(this)
    this.state = {
        isSendingResetLink: false
    }

    message.config({
      maxCount: 1,
    });

  }

  sendResetLink (e) {
    e.preventDefault();
    this.setState({
      isSendingResetLink: true
    })

    this.props.form.validateFields((err, form) => {
      if (!err) {
        console.log('Received values of form: ', form);
        axios.post('/t/api/password/email', form).then((res) => {
            console.log(res.data)
            this.setState({
              isSendingResetLink: false
            });
            message.success(res.data.message, false);
            return;
        }).catch((err) => {
            console.log(err.response.data);
            message.error( err.response.data.errors.email instanceof Array ?  err.response.data.errors.email[0] : err.response.data.errors.email , 25);
            this.setState({
              isSendingResetLink: false
            })
        })
      } else {
        this.setState({
          isSendingResetLink: false
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
                       Enter the email address you registered with and we will send you a link to reset your password
                    </p>


                    <Form onSubmit={this.handleSubmit} className="login-form">
                      <Form.Item>
                        <label htmlFor="email">Email address</label>
                        {getFieldDecorator('email', {
                          rules: [{ required: true, message: 'Please input your email!' }],
                        })(
                          <Input
                            size="large"
                            type="email"
                            placeholder="jonathanadamu@mymail.com"
                          />,
                        )}
                      </Form.Item>

                      <Form.Item className="is-full-width">
                        <Button
                          disabled={this.state.isSendingResetLink}
                          loading={this.state.isSendingResetLink}
                          onClick={this.sendResetLink}
                          type="primary"
                          htmlType="submit"
                          className="auth__btn">
                          Get password reset link
                        </Button>
                      </Form.Item>
                      <p>Back to login</p>
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