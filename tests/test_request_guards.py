"""Run real PHP denial paths without any database or live account."""
import json,os,pathlib,shlex,subprocess,unittest
ROOT=pathlib.Path(__file__).resolve().parents[1]
PHP=shlex.split(os.environ.get('PHP_BINARY','php'))
ENDPOINT='borrar.php'
HELPER='security.php'
AUTH="$_SESSION['k_username']='test-user';"
GUARD='security_require_login();'
class RequestGuardTests(unittest.TestCase):
 def execute(self,code):
  prefix="error_reporting(E_ALL); register_shutdown_function(function(){fwrite(STDERR, 'STATUS='.(http_response_code() ?: 200));}); $_SERVER['REQUEST_METHOD']='POST'; session_start(); $_SESSION['csrf_token']=str_repeat('a',64); "
  r=subprocess.run(PHP+['-r',prefix+code],cwd=ROOT,capture_output=True,text=True)
  self.assertEqual(r.returncode,0,r.stdout+r.stderr)
  self.assertNotIn('Fatal error',r.stdout+r.stderr)
  status=int(r.stderr.rsplit('STATUS=',1)[1]);return status,r.stdout
 def test_anonymous_endpoint_is_denied_before_database_access(self):
  status,_=self.execute("$_GET['job']='delete_company'; $_GET['id']='1'; $_POST['type']=1; require "+json.dumps(str(ROOT/ENDPOINT))+";")
  self.assertEqual(status,403)
 def test_authenticated_request_without_csrf_is_denied(self):
  status,_=self.execute(AUTH+"require "+json.dumps(str(ROOT/HELPER))+"; security_require_post();")
  self.assertEqual(status,403)
 def test_matching_token_is_accepted(self):
  status,body=self.execute(AUTH+"$_POST['csrf_token']=str_repeat('a',64); require "+json.dumps(str(ROOT/HELPER))+"; "+GUARD+" security_require_post(); echo 'accepted';")
  self.assertEqual(status,200);self.assertEqual(body,'accepted')
if __name__=='__main__':unittest.main()
