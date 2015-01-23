/**
 * 
 */
package com.hummingbird.smsmarketing.service.impl;

import static org.junit.Assert.fail;

import org.junit.After;
import org.junit.Before;
import org.junit.Test;
import org.junit.runner.RunWith;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.jdbc.core.JdbcTemplate;
import org.springframework.test.context.ContextConfiguration;
import org.springframework.test.context.junit4.AbstractJUnit4SpringContextTests;
import org.springframework.test.context.junit4.SpringJUnit4ClassRunner;

import com.hummingbird.smsmarketing.entity.Rouji;
import com.hummingbird.smsmarketing.service.QueueManager;
import com.hummingbird.smsmarketing.vo.DefaultIssueSendRequest;
import com.hummingbird.smsmarketing.vo.DefaultRequestAttrSet;
import com.hummingbird.smsmarketing.vo.ITaskSet;
import com.hummingbird.smsmarketing.vo.IssueSendRequest;

/**
 * @author huangjiej_2
 * 2014年12月9日 下午9:54:49
 */
@RunWith(SpringJUnit4ClassRunner.class)
@ContextConfiguration({ "classpath:dataSource.xml","classpath:applicationContext.xml" }) 
public class TaskQueueManagerTest extends AbstractJUnit4SpringContextTests {

	@Autowired
	QueueManager tq;
	
	/**
	 * @throws java.lang.Exception
	 */
	@Before
	public void setUp() throws Exception {
	}

	/**
	 * @throws java.lang.Exception
	 */
	@After
	public void tearDown() throws Exception {
	}

	/**
	 * Test method for {@link com.hummingbird.smsmarketing.service.impl.TaskQueueManager#getTasks(com.hummingbird.smsmarketing.vo.IssueSendRequest)}.
	 */
	@Test
	public void testGetTasks() {
//		JdbcTemplate jdbcTemplate = this.applicationContext.getBean(JdbcTemplate.class);
//		
//		
//		DefaultIssueSendRequest sendReq=new DefaultIssueSendRequest();
//		Rouji rouji = new Rouji();
//		rouji.setMobilenum("13939393939");
//		sendReq.setIssueSender(rouji);
//		sendReq.setRequestTaskCount(3);
//		ITaskSet tasks = tq.getTasks(sendReq);
	}

	/**
	 * Test method for {@link com.hummingbird.smsmarketing.service.impl.TaskQueueManager#rechargeQueue()}.
	 */
	@Test
	public void testRechargeQueue() {
//		fail("Not yet implemented");
	}

	/**
	 * Test method for {@link com.hummingbird.smsmarketing.service.impl.TaskQueueManager#confirmTask(com.hummingbird.smsmarketing.vo.ITask)}.
	 */
	@Test
	public void testConfirmTask() {
//		fail("Not yet implemented");
	}

	/**
	 * Test method for {@link com.hummingbird.smsmarketing.service.impl.TaskQueueManager#support(com.hummingbird.smsmarketing.vo.IssueSendRequest)}.
	 */
	@Test
	public void testSupport() {
//		fail("Not yet implemented");
	}

}
