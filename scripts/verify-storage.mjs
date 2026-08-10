import fs from 'node:fs';
import path from 'node:path';
import { fileURLToPath } from 'node:url';
import SftpClient from 'ssh2-sftp-client';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const root = path.resolve(__dirname, '..');
const sftpConfig = JSON.parse(fs.readFileSync(path.join(root, '.vscode', 'sftp.json'), 'utf8'));
const storageDir = '/home/u467937533/domains/kmxmedia.com/private/work-requests';
const requestId = process.argv[2];

const sftp = new SftpClient();
try {
  await sftp.connect({
    host: sftpConfig.host,
    port: sftpConfig.port,
    username: sftpConfig.username,
    password: sftpConfig.password,
  });
  const exists = await sftp.exists(storageDir);
  console.log('storage_dir_exists:', exists);
  const list = await sftp.list(storageDir);
  console.log('files:', list.map((f) => f.name).join(', ') || '(empty)');
  if (requestId) {
    const remoteFile = `${storageDir}/${requestId}.json`;
    const fileExists = await sftp.exists(remoteFile);
    console.log('target_file:', remoteFile);
    console.log('target_exists:', fileExists);
    if (fileExists) {
      const buf = await sftp.get(remoteFile);
      const json = JSON.parse(buf.toString('utf8'));
      console.log('status:', json.status);
      console.log('business_name:', json.business_name);
      console.log('estimator_package:', json.estimator_snapshot?.recommended_package);
    }
  }
} finally {
  await sftp.end();
}